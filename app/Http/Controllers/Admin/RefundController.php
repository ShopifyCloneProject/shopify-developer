<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\EmailService;
use Razorpay\Api\Api;
use paytm\paytmchecksum\PaytmChecksum;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\RefundProduct;
use App\Models\Product;
use App\Models\Refund;
use App\Models\ReturnOrder;
use App\Models\ReturnOrderProduct;
use App\Models\User;
use Config;
use Exception;
use Gate;
use Log;

class RefundController extends Controller
{
    private $payment_method_id;
    private $note;
    private $amount;
    private $payment_status;
    private $strPaymentId;

    protected $emailService;

    public function __construct(){
        $this->emailService = new EmailService;
    }
    
    /**
    * Redirect the user to the Payment Gateway RazorpayPay.
    *
    * @return Response
    */
    public function refundRazorpayPay($objOrder){
        try{
            
            if(is_numeric( $this->amount ) && floor( $this->amount ) != $this->amount)
            {
                list($amount1,$amount2) = explode(".", $this->amount);
                $refundamount = $amount1.$amount2;
            }
            else
            {
                $refundamount = $this->amount * 100;
            }
            
            $refundData = [
                            "amount" => $refundamount,
                            "speed" => "normal"
                          ];
            $client_id = Config::get('client_id');                          
            $objRazorpayDetail = PaymentDetail::where(['user_id' => $client_id, 'payment_method_id' => 1])->first(); 
            $api = new Api($objRazorpayDetail->app_key, $objRazorpayDetail->app_secret);
            $objRefundPayment = $api->payment->fetch($this->strPaymentId)->refund($refundData);
            $this->payment_status = $objRefundPayment->status;
            $this->storeRefunds($objRefundPayment->toArray());
            if(in_array($this->payment_status, ["pending", "processed"]))
            {
                return "SUCCESS"; 
            }
             return "FAILED"; 

        } catch (Exception $e) {
            Log::info($objOrder->order_nr,$e->getMessage());
            return "SOMETHING";
        }
    }

    /**
    * Redirect the user to the Payment Gateway Paytm.
    *
    * @return Response
    */
    public function refundPaytm($objOrder){
        try{
            
            $client_id = Config::get('client_id');
            $objPaytmPaymentDetail = PaymentDetail::where(['user_id' => $client_id, 'payment_method_id' => 2])->first(); 
            $orderid = $objOrder->order_nr;
            $refrence_id = mt_rand(100, 999);
            $paytmParams = [];

            $paytmParams["body"] = [
                "mid"          => $objPaytmPaymentDetail->app_key,
                "txnType"      => "REFUND",
                "orderId"      => $orderid,
                "txnId"        => $this->strPaymentId,
                "refId"        => "REFUNDID_".$orderid.$refrence_id,
                "refundAmount" => (string) $this->amount
            ];

            $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $objPaytmPaymentDetail->app_secret);

            $paytmParams["head"] = ["signature" => $checksum];

            $isTestMode = "securegw-stage";
            if(!empty($objPaytmPaymentDetail))
            {
                if($objPaytmPaymentDetail->is_testmode == 0)
                {
                    $isTestMode = "securegw";
                }
            }
            $paytmurl = "https://".$isTestMode.".paytm.in/refund/apply";
            $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
            $ch = curl_init($paytmurl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); 
            $response = curl_exec($ch);
            $response = json_decode($response, true);
            $this->payment_status = $response['body']['resultInfo']['resultStatus'];
            $this->storeRefunds($response);
            if(in_array($response['body']['resultInfo']['resultStatus'], ["PENDING"]) && $response['body']['resultInfo']['resultCode'] == 601)
            {
                 return "SUCCESS";
            }
            return "FAILED";

        } catch (Exception $e) {
            Log::info($objOrder->order_nr,$e->getMessage());
            return "SOMETHING";
        }
    }

    /**
    * Redirect the user to the Payment Gateway Cashfree.
    *
    * @return Response
    */
    public function refundCashfree($objOrder){
        try{
            $strPaymentId = $this->strPaymentId;
            $refrence_id = mt_rand(100, 999);
            $orderid = $objOrder->order_nr.$refrence_id;
            $client_id = Config::get('client_id');
            $objCashfreePaymentDetail = PaymentDetail::where(['user_id' => $client_id, 'payment_method_id' => 4])->first();
            $isTestMode = "sandbox";
             if(!empty($objCashfreePaymentDetail))
             {
                if($objCashfreePaymentDetail->is_testmode == 0)
                {
                    $isTestMode = "api";
                }
             }
             $cashfreeurl = "https://".$isTestMode.".cashfree.com/pg/orders/$strPaymentId/refunds";
            $cashFreeDetail = PaymentDetail::where('payment_method_id', 4)->first();
             $postData = [
              "refund_id" => "REFUNDID_".$orderid,
              "refund_amount" => $this->amount,
              "refund_note" => "REFUND"
             ];

            $client = new \GuzzleHttp\Client();
            $response = $client->post($cashfreeurl, [
              'body' => json_encode($postData),
              'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/json',
                'x-client-id' => $cashFreeDetail->app_key,
                'x-client-secret' =>  $cashFreeDetail->app_secret,
                'x-api-version' => '2022-09-01'
              ],
            ]);
             $response = json_decode($response->getBody(), true);
             $this->payment_status = $response['refund_status'];
             $this->storeRefunds($response);
        
            if($response['entity'] == "refund" && in_array($response['refund_status'],["PENDING","SUCCESS"]))
            {
                 return "SUCCESS";
            }
           
            return "FAILED";
        } catch (Exception $e) {
            Log::info($objOrder->order_nr,$e->getMessage());
            return "SOMETHING";
        }
    }

    /**
    * Redirect the user to the Payment Gateway Instamojo.
    *
    * @return Response
    */
     public function refundInstamojo($objOrder){
        try{
            $strPaymentId = $this->strPaymentId;
            $client_id = Config::get('client_id');
            $refrence_id = mt_rand(100, 999);
            $orderid = $objOrder->order_nr.$refrence_id;
            $instamojoDetail = PaymentDetail::where(['user_id' => $client_id, 'payment_method_id' => 5])->first();

            $isTestMode = "test";
             if(!empty($instamojoDetail))
             {
                if(!$instamojoDetail->is_testmode)
                {
                    $isTestMode = "api";
                }
            }

            $generateTokenRequest = "https://".$isTestMode.".instamojo.com/oauth2/token/";
            $generatePaymentRequest = "https://".$isTestMode.".instamojo.com/v2/payments/$strPaymentId/refund/";

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
             $payload = [
                'refund_amount' => $this->amount,
                'transaction_id' => $this->strPaymentId,
                'body'          => "REFUNDID_".$orderid,
                'type'          => 'TNR'
            ];

            $response = $client->post($generatePaymentRequest, [
              'form_params' => $payload,
              'headers' => [
                'Authorization' => 'Bearer '.$finalAccessToken['access_token'],
                'accept' => 'application/json',
                'content-type' => 'application/x-www-form-urlencoded',
              ],
            ]);
            $response = json_decode($response->getBody(), true);
            $this->payment_status = $response['refund']['status'];
            $this->storeRefunds($response);
            
            if($response['success'])
            {
                return "SUCCESS";
            }

            return "FAILED";
             
        } catch (Exception $e) {
            Log::info($objOrder->order_nr,$e->getMessage());
            return "SOMETHING";
        }
    }

    public function refundCOD($objOrder){
        try{
            $payload = [
                'refund_amount' => $this->amount,
                'type'          => 'COD',
                'orderid'       => $objOrder->order_nr
            ];

            $this->payment_status = "refund";
            $this->storeRefunds($payload);
            return "SUCCESS";

        } catch (Exception $e) {
            return "SOMETHING";
        }
    }

    public function getPaymentId($orderid)
    {
        $payment_id = "";
        $objPayment = Payment::where('order_id', $orderid)->latest()->first();
        if(!empty($objPayment))
        {
            $payment_id = $objPayment->payment_id;
        }
        return $payment_id;
    }

    public function getOrderData($orderid)
    {
        $objOrderData = [];
        $objOrder = Order::whereId($orderid)->latest()->first();
        if(!empty($objOrder))
        {
            $this->payment_method_id = $objOrder->payment_method_id;
            return $objOrder;
        }
        return $objOrderData;
    }

    public function getOrderRefundAmount($orderid)
    {
        return  Refund::where('order_id', $orderid)->get()->sum('amount');
    }

    public function storeRefunds($data)
    {
        $objRefund = new Refund;
        $objRefund->order_id = $this->orderid;
        $objRefund->payment_id = $this->strPaymentId;
        if($this->payment_method_id == 1)
        {
            $objRefund->refund_id = $data['id'];
        }
        elseif($this->payment_method_id == 2)
        {
            $objRefund->refund_id = $data['body']['refundId'];
        }
        elseif($this->payment_method_id == 3)
        {
            $objRefund->refund_id = $data['id'];
        }
        elseif($this->payment_method_id == 4)
        {
            $objRefund->refund_id = $data['cf_refund_id'];
        }
        elseif($this->payment_method_id == 5)
        {
            $objRefund->refund_id = $data['refund']['id'];
        }
        elseif($this->payment_method_id == 6)
        {
            $objRefund->refund_id = $data['orderid'];
        }
        $objRefund->payment_method_id = $this->payment_method_id;
        $objRefund->amount = $this->amount;
        $objRefund->status = $this->payment_status;
        $objRefund->note = $this->note;
        $objRefund->data = json_encode($data);
        $objRefund->save();

    }

    public function refundProduct(Request $request){
        try {
            $refundProductDetail = [];
            $orderQuantity = 0;
            $params = collect($request->all());
            $objReturnOrderUserId = Order::with('order_products')
            ->with(['order_products'=>function($orderproductquery){
                $orderproductquery->select('order_id','quantity','product_id','product_variant_options_id');
            }])->select('id','user_id')->whereId($params['order_id'])->first();

            $this->note =  $params['note'];
            $this->amount = round($params['currentRefundAmount'],2); // currentRefundAmount 
            $objOrder = $this->getOrderData($params['order_id']);
            $orderAlreadyRefundAmount =  $this->getOrderRefundAmount($params['order_id']);
            $remianingAmount = $objOrder->total - $orderAlreadyRefundAmount;
            if($remianingAmount < $this->amount)
            {
                 return $this->successResponse(
                    __('constants.ERROR_STATUS'),
                    __('constants.errors.AMOUNT_GRETER_THAN.code'),
                    __('constants.errors.AMOUNT_GRETER_THAN.msg'),
                    []
                );
            }
            $refundResponseStatus = "FAILED";
            if(env('APP_ENV') == 'local')
            {
                $refundResponseStatus = "SUCCESS";   
            }
            else
            {
                $this->orderid = $params['order_id'];
                $this->payment_method_id = $objOrder->payment_method_id;
                $this->strPaymentId = $this->getPaymentId($params['order_id']);
                if($this->payment_method_id == 1)
                {
                   $refundResponseStatus = $this->refundRazorpayPay($objOrder);
                }
                elseif($this->payment_method_id  == 2)
                {
                   $refundResponseStatus = $this->refundPaytm($objOrder);
                }
                elseif($this->payment_method_id  == 3)
                {
                    // will do for payzed
                }
                elseif($this->payment_method_id  == 4)
                {
                    $refundResponseStatus = $this->refundCashfree($objOrder);
                }
                elseif($this->payment_method_id  == 5)
                {
                    $refundResponseStatus = $this->refundInstamojo($objOrder);
                }
                elseif($this->payment_method_id  == 6)
                {
                    $refundResponseStatus = $this->refundCOD($objOrder);
                }
            }
            if($refundResponseStatus == "SUCCESS")
            {
            
                foreach($params['refundProducts'] as $key => $refundProduct){
                    if($refundProduct['isApprove']){
                    $objRefundProduct = new RefundProduct;
                    $objRefundProduct->order_id = $params['order_id'];
                    $objRefundProduct->user_id = $objReturnOrderUserId->user_id;
                    $objRefundProduct->product_id = $refundProduct['product_id'];
                    $objRefundProduct->product_variant_options_id = $refundProduct['product_variant_option_id'];
                    $objRefundProduct->title = $refundProduct['title'];
                    $objRefundProduct->slug = $refundProduct['slug'];
                    $objRefundProduct->cost_per_item = $refundProduct['cost_per_item'];
                    $objRefundProduct->quantity = $refundProduct['currentRefundQuantity'];
                    $objRefundProduct->price = $refundProduct['price'];
                    $objRefundProduct->total = $refundProduct['refundAmount'];
                    $objRefundProduct->src = $refundProduct['img_src'];
                    $objRefundProduct->sku = $refundProduct['sku'];
                    $objRefundProduct->barcode = $refundProduct['barcode'];
                    if(isset($refundProduct['weight_type_id'])){
                        $objRefundProduct->weight_type_id = $refundProduct['weight_type_id'];
                    }
                    $objRefundProduct->weight = $refundProduct['weight'];
                    if(isset($refundProduct['length_type_id'])){
                        $objRefundProduct->length_type_id = $refundProduct['length_type_id'];
                    }
                    $objRefundProduct->length = $refundProduct['length'];
                    if(isset($refundProduct['width_type_id'])){
                        $objRefundProduct->width_type_id = $refundProduct['width_type_id'];
                    }
                    $objRefundProduct->width = $refundProduct['width'];
                    if(isset($refundProduct['height_type_id'])){
                        $objRefundProduct->height_type_id = $refundProduct['height_type_id'];
                    }
                    $objRefundProduct->height = $refundProduct['height'];
                    $objRefundProduct->hs_code = $refundProduct['hs_code'];
                    $objRefundProduct->is_product_charge = $refundProduct['is_product_charge'];
                    $objRefundProduct->is_track = $refundProduct['is_track'];
                    $objRefundProduct->is_special_product = $refundProduct['is_special_product'];
                    $objRefundProduct->special_price = $refundProduct['special_price'];
                    $objRefundProduct->description = $refundProduct['approveOrderDescription'];
                    $objRefundProduct->save();

                    $orderQuantity = $objReturnOrderUserId->order_products->filter(function ($item) use($refundProduct,$objReturnOrderUserId) {
                                return $item->product_id == $refundProduct['product_id'] && $item->product_variant_options_id == $refundProduct['product_variant_option_id'];
                            })->first()->quantity;
                    
                    $refundProductDetail[] = [
                        'refundProductTitle' => $refundProduct['title'],
                        'refundQuantity'     => $refundProduct['currentRefundQuantity'],
                        'orderedQuantity' => $orderQuantity,
                    ];
                    }
                }
                
                $objOrder = Order::with('currency','order_products', 'refund_products')->whereId($params['order_id'])->latest()->first();
                $objUser = User::whereId($objOrder->user_id)->first();

                $data =[
                    'adminemail' => config('contactEmail'),
                    'adminContact' => config('contactNo'),

                    'refundProducts' => $refundProductDetail,
                    'currencySymbol' => $objOrder->currency->symbol,
                    'email' => $objOrder->email,
                    'fullName' => $objUser->fullname,
                    'orderNumber' => $objOrder->order_nr,
                    'refundAmount' => $objOrder->refund_products->sum('total'),
                ];

                if(!empty($objOrder)){
                    $objTotalOrderProduct = OrderProduct::where('order_id',$objOrder->id)->get()->sum('quantity');
                    $objTotalRefundProduct = RefundProduct::where('order_id',$params['order_id'])->get()->sum('quantity');

                    $financial_status = 'partially_refunded';
                    if($objTotalOrderProduct == $objTotalRefundProduct){
                        $financial_status = 'refunded';
                    }
                    $objOrder->update(['financial_status'=>$financial_status]);

                    $objReturnOrder = ReturnOrder::where('order_id',$params['order_id'])->latest()->first();
                    if(!empty($objReturnOrder))
                    {
                        $objReturnOrderProductNotApproveCount = ReturnOrderProduct::where(['return_order_id' => $objReturnOrder->id, 'admin_approve' => 0])->count();
                        if($objReturnOrderProductNotApproveCount == 0)
                        {
                            ReturnOrder::where('order_id',$params['order_id'])->update(['admin_approve' => 1]);
                        }
                    }
                }
                $this->emailService->orderRefund($data);
                $url = route('admin.orders.index');
                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.ORDER_REFUND_SUCCESSFULLY.code'),
                    __('constants.messages.ORDER_REFUND_SUCCESSFULLY.msg'),
                    ['url' => $url]
                );
            }
            else
            {
                 return $this->errorResponse(
                    __('constants.ERROR_STATUS'),
                    __('constants.errors.SOMETHING_WRONG.code'),
                    __('constants.errors.SOMETHING_WRONG.msg'),
                );
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function refundRequestSuccess(){
        $data = [];
        $orderId = '0c6de670-a910-11ed-a2ea-d9d150134359';
        $orderDetails = Order::with('user')->with('user', function($userQuery){
                            $userQuery->select('id','name','email');
                        })->select('id','order_nr','user_id','total')->latest()->first();
        $objRefund = Refund::where(['order_id'=>$orderId,'status'=>'refunded'])->first();
        if(!empty($objRefund)){
            $refund_at = $objRefund->created_at;
        }

        $data = [
            'adminemail' => config('contactEmail'),
            'adminContact' => config('contactNo'),
            'orderDetails' => $orderDetails, 
            'refund_at'    => $refund_at, 
        ];

        dd($data);
    // $this->emailService->orderRefund($data);
        dd('refund request success mail sent');
    }

    // public function refundSucessTemplate(){
    //     $data = [];
    //     $orderId = "7d995e40-4aaf-11ed-812a-f544422bf803";
    //      $objOrder = Order::with('currency','order_products', 'refund_products')->whereId($orderId)->latest()->first();
    //      $objUser = User::whereId($objOrder->user_id)->first();

    //      if(!empty($objOrder)){
    //         $refundProductDetail = [];
    //         $refundProductId = $objOrder->refund_products->pluck('product_id')->toArray();

    //         if(!empty($objOrder->refund_products)){
    //             forEach($objOrder->refund_products as $key => $objRefundProduct){
    //                 $orderQuantity = $objOrder->order_products->filter(function ($item) use($objRefundProduct) {
    //                             return $item->product_id == $objRefundProduct->product_id && $item->product_variant_options_id == $objRefundProduct->product_variant_options_id;
    //                         })->first()->quantity;
    //                 $refundProductDetail[] = [
    //                     'refundProductTitle' => $objRefundProduct->title,
    //                     'refundQuantity' => $objRefundProduct->quantity,
    //                     'orderedQuantity' => $orderQuantity,
    //                 ];
    //             }
    //         $data =[
    //         'adminemail' => config('contactEmail'),
    //         'adminContact' => config('contactNo'),
    //         'refundProducts' => $refundProductDetail,
    //         'currencySymbol' => $objOrder->currency->symbol,
    //         'email' => $objOrder->email,
    //         'fullName' => $objUser->fullname,
    //         'orderNumber' => $objOrder->order_nr,
    //         'refundAmount' => $objOrder->refund_products->sum('total'),
    //         ];
    //         $this->emailService->orderRefund($data);
    //         dd('refund mail sent');
    //         }
    //      }
    // }
}
