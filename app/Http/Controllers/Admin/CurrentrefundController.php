<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use paytm\paytmchecksum\PaytmChecksum;

use App\Models\PaymentDetail;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\Order;

use Config;

class CurrentrefundController extends Controller
{
     public function statusRazorpay($objRefund)
    {
        try {
            $objRazorpayDetail = PaymentDetail::where('payment_method_id', 1)->first();
            $api = new Api($objRazorpayDetail->app_key, $objRazorpayDetail->app_secret);
            $objRefundData = $api->payment->fetch($objRefund->payment_id)->fetchRefund($objRefund->refund_id);
            if(!empty($objRefund))
            {   
                if($objRefundData['status'] == "processed")
                {
                    $objRefund->refund_status = 'refund';
                }
                $objRefund->current_data = json_encode(collect($objRefundData));
                $objRefund->save();
            }
        } catch (Exception $e) {
            
        }
    }

    public function statusPaytm($objRefund)
    {
        try {
        $client_id = Config::get('client_id');
        $paytmDetails = PaymentDetail::where('payment_method_id', 2)->first();
        $paytmParams = [];
        $paytmParams["body"] = [
            "mid" => $objRefund->decode_data["body"]['mid'],
            "orderId" => $objRefund->decode_data["body"]['orderId'],
            "refId" => $objRefund->decode_data["body"]['refId']
        ];
        $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $paytmDetails->app_secret);
        $objPaytmPaymentDetail = PaymentDetail::where(['user_id' => $client_id, 'payment_method_id' => 2])->first();
        $paytmurlmode = "securegw-stage";
        if(!empty($objPaytmPaymentDetail))
        {
            if($objPaytmPaymentDetail->is_testmode == 0)
            {
                $paytmurlmode = "securegw";
            }
        }
        $paytmurl = "https://".$paytmurlmode.".paytm.in/v2/refund/status";
        $paytmParams["head"] = ["signature" => $checksum];
        $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
        $ch = curl_init($paytmurl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);
        $response = json_decode($response, true);
        if(!empty($objRefund))
        {   
            if($response['body']['resultInfo']['resultCode'] == "10")
            {
                $objRefund->refund_status = 'refund';
                $objRefund->refund_id = $response['body']['refundId'];
            }
            $objRefund->current_data = json_encode($response);
            $objRefund->save();
        }
            
        } catch (Exception $e) {
            
        }
    }

    public function statusCashfree($objRefund)
    {
        try {
            $client_id = Config::get('client_id');
            $objCashfreePaymentDetail = PaymentDetail::where(['user_id' => $client_id, 'payment_method_id' => 4])->first();
            $cashfreeurlmode = "sandbox";
             if(!empty($objCashfreePaymentDetail))
             {
                if($objCashfreePaymentDetail->is_testmode == 0)
                {
                    $cashfreeurlmode = "api";
                }
             }
            $payment_id = $objRefund->decode_data['order_id'];
            $refund_id = $objRefund->decode_data['refund_id'];

            $cashfreeurl = "https://".$cashfreeurlmode.".cashfree.com/pg/orders/".$payment_id."/refunds/".$refund_id;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', $cashfreeurl, [
              'headers' => [
                'accept' => 'application/json',
                'x-api-version' => '2022-09-01',
                'x-client-id' => $objCashfreePaymentDetail->app_key,
                'x-client-secret' => $objCashfreePaymentDetail->app_secret,
              ],
            ]);

            $response = json_decode($response->getBody(), true);
          
            if(!empty($objRefund))
            {   
                if($response['refund_status'] == "SUCCESS")
                {
                    $objRefund->refund_status = 'refund';
                }
                $objRefund->current_data = json_encode($response);
                $objRefund->save();
            }
        } catch (Exception $e) {
            
        }
    }

    public function statusInstamojo($objRefund)
    {
        $instamojoDetail = PaymentDetail::where('payment_method_id', 5)->first();
        $isTestMode = "test";
        if(!$instamojoDetail->is_testmode)
        {
            $isTestMode = "api";
        }

        $generateTokenRequest = "https://".$isTestMode.".instamojo.com/oauth2/token/";
        $getRefundStatusRequest = "https://".$isTestMode.".instamojo.com/v2/resolutioncenter/cases/".$objRefund->refund_id."/";

        $client = new \GuzzleHttp\Client();

        $response = $client->post($generateTokenRequest, [
          'form_params' => [
            'grant_type' => 'client_credentials',
            'client_id' => $instamojoDetail->app_key,
            'client_secret' => $instamojoDetail->app_secret
          ],
          'headers' => [
            'accept' => 'application/json',
            'content-type' => 'application/x-www-form-urlencoded',
          ],
        ]);
        $finalAccessToken = json_decode($response->getBody(), true);
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', $getRefundStatusRequest, [
          'headers' => [
            'Authorization' => 'Bearer '.$finalAccessToken['access_token'],
            'accept' => 'application/json',
          ],
        ]);
        $response =  json_decode($response->getBody(), true);
        if(!empty($objRefund))
        {   
            if($response['status'] == "FINI")
            {
                $objRefund->refund_status = 'refund';
            }
            $objRefund->current_data = json_encode($response);
            $objRefund->save();
        }
    }

    public function refundStatus(Request $request)
    {
        try {
            $params = collect($request->all());
            $intRefundId = $params['main_refund_id'];
            $objRefund = Refund::whereId($intRefundId)->first();
            $objOrder = Order::whereId($objRefund->order_id)->first();
            $objResponse = [];
            if(!empty($objOrder))
            {
                if($objOrder->payment_method_id == 1)
                {
                    $this->statusRazorpay($objRefund);
                }
                elseif ($objOrder->payment_method_id == 2) {
                    $this->statusPaytm($objRefund);      
                }
                elseif ($objOrder->payment_method_id == 3) {
                    // will do
                }
                elseif ($objOrder->payment_method_id == 4) {
                    $this->statusCashfree($objRefund);    
                }
                elseif ($objOrder->payment_method_id == 5) {
                    $this->statusInstamojo($objRefund);
                }
               
                return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.REFUND_CURRENT_STATUS_SUCCESSFULLY.code'),
                __('constants.messages.REFUND_CURRENT_STATUS_SUCCESSFULLY.msg'),
                $objRefund,
                );
            }
        } catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }

    }
}
