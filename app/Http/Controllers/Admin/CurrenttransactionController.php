<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use paytm\paytmchecksum\PaytmChecksum;

use App\Models\PaymentDetail;
use App\Models\Payment;
use App\Models\Order;

use Config;

class CurrenttransactionController extends Controller
{
    public function statusRazorpay($objPayment)
    {
        try {
            $objRazorpayDetail = PaymentDetail::where('payment_method_id', 1)->first();
            $api = new Api($objRazorpayDetail->app_key, $objRazorpayDetail->app_secret);
            $payment = $api->payment->fetch($objPayment->payment_id);
            if(!empty($objPayment))
            {   
                if($payment['status'] == "captured")
                {
                    $objPayment->payment_status = 'paid';
                }
                $objPayment->current_data = json_encode(collect($payment));
                $objPayment->save();
            }
            
        } catch (Exception $e) {
            
        }
    }

    public function statusPaytm($objPayment)
    {
        try {
        $client_id = Config::get('client_id');
        
        $paytmDetails = PaymentDetail::where('payment_method_id', 2)->first();
        $paytmParams = [];

        $paytmParams["body"] = [
            "mid" => $paytmDetails->app_key,
            "orderId" => $objPayment->decode_data['ORDERID']
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
        $paytmurl = "https://".$paytmurlmode.".paytm.in/v3/order/status";
        $paytmParams["head"] = ["signature" => $checksum];
        $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
        $ch = curl_init($paytmurl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);
        $response = json_decode($response, true);
        if(!empty($objPayment))
        {   
            if($response['body']['resultInfo']['resultCode'] == "01")
            {
                $objPayment->payment_status = 'paid';
            }
            $objPayment->current_data = json_encode($response);
            $objPayment->save();
        }
            
        } catch (Exception $e) {
            
        }
    }

    public function statusCashfree($objPayment)
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
            $cashfreeurl = "https://".$cashfreeurlmode.".cashfree.com/pg/orders/".$objPayment->payment_id."/payments";
            
                $curl = curl_init();
                curl_setopt_array($curl, [
                  CURLOPT_URL => $cashfreeurl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "GET",
                  CURLOPT_HTTPHEADER => [
                    "accept: application/json",
                    "x-api-version: 2022-09-01",
                    "x-client-id: ".$objCashfreePaymentDetail->app_key,
                    "x-client-secret: ".$objCashfreePaymentDetail->app_secret
                  ],
                ]);
            $response = curl_exec($curl);
            $response =  json_decode($response, true)[0];
            if(!empty($objPayment))
            {   
                if($response['payment_status'] == "SUCCESS")
                {
                    $objPayment->payment_status = 'paid';
                }
                $objPayment->current_data = json_encode($response);
                $objPayment->save();
            }
        } catch (Exception $e) {
            
        }
    }

    public function statusInstamojo($objPayment)
    {
       $instamojoDetail = PaymentDetail::where('payment_method_id', 5)->first();
            $isTestMode = "test";
            if(!$instamojoDetail->is_testmode)
            {
                $isTestMode = "api";
            }

            $generateTokenRequest = "https://".$isTestMode.".instamojo.com/oauth2/token/";
            $getPaymentStatusRequest = "https://".$isTestMode.".instamojo.com/v2/payments/".$objPayment->payment_id."/";

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

            $response = $client->request('GET', $getPaymentStatusRequest, [
              'headers' => [
                'Authorization' => 'Bearer '.$finalAccessToken['access_token'],
                'accept' => 'application/json',
              ],
            ]);
            $response =  json_decode($response->getBody(), true);

            if(!empty($objPayment))
            {   
                if($response['status'] == "Completed")
                {
                    $objPayment->payment_status = 'paid';
                }
                $objPayment->current_data = json_encode($response);
                $objPayment->save();
            }
    }

    public function paymentStatus(Request $request)
    {
        try {
            $params = collect($request->all());
            $strPaymentId = $params['main_payment_id'];
            $objPayment = Payment::whereId($strPaymentId)->first();
            $objOrder = Order::whereId($objPayment->order_id)->first();
            $objResponse = [];
            if(!empty($objOrder))
            {
                if($objOrder->payment_method_id == 1)
                {
                    $this->statusRazorpay($objPayment);
                    $objResponse = $objPayment->decode_current_data;      
                }
                elseif ($objOrder->payment_method_id == 2) {
                    $this->statusPaytm($objPayment); 
                    $objResponse = $objPayment->decode_current_data['body'];     
                }
                elseif ($objOrder->payment_method_id == 3) {
                    // will do
                }
                elseif ($objOrder->payment_method_id == 4) {
                    $this->statusCashfree($objPayment);
                    $objResponse = $objPayment->decode_current_data;    
                }
                elseif ($objOrder->payment_method_id == 5) {
                    $this->statusInstamojo($objPayment);
                    $objResponse = $objPayment->decode_current_data;      
                }

                return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PAYMENT_CURRENT_STATUS_SUCCESSFULLY.code'),
                __('constants.messages.PAYMENT_CURRENT_STATUS_SUCCESSFULLY.msg'),
                  $objResponse
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
