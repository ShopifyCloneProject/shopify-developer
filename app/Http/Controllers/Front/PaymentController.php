<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

use Razorpay\Api\Api;
use App\Models\Order;
use App\Models\Payment;
use App\Models\OrderProduct;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\PaymentDetail;
use App\Models\InventoryStock;
use App\Models\Address;
use App\Models\OrderLocation;
use App\Models\User;
use App\Services\EmailService;

use Session;
use Redirect;
use Auth;
use Config;
use Exception;
use Helper;

class PaymentController extends Controller
{
    private $paymentMode = NULL;
    private $orderId;
    private $fulfillmentStatus = 'unfulfilled';
    private $financialStatus;
    private $macAddr;
    protected $emailService;

    public function __construct(){
        $this->macAddr = Helper::getMacAddress();
        $this->emailService = new EmailService;
    }

    /**
    * Success method after successfully payment made by razropay Payment Gateway.
    *
    * @return Request
    */
    public function paymentRazorpay(Request $request)
    {
        if(isset($request->razorpay_payment_id)){
            //payment success
            $input = $request->all();
            $objRazorpayDetail = PaymentDetail::where('payment_method_id', 1)->first();
            $api = new Api($objRazorpayDetail->app_key, $objRazorpayDetail->app_secret);
            $payment = $api->payment->fetch($request->razorpay_payment_id);
            $this->orderId = $payment['notes']['merchant_order_id'];
            $this->paymentMode = $payment['method'];
            $this->financialStatus = 'paid';

            $capturedData = [
                "amount" => $payment['amount'],
                "currency" => $payment['currency']
            ];

            $payment = $api->payment->fetch($request->razorpay_payment_id)->capture($capturedData);

            $this->store(collect($payment));

            $this->setLiveUserCount('purchase');
            $order = Order::where('order_nr', $this->orderId)->first();
            $order->flash_status = 1;
            $order->save(); 
            $orderId = $order->id;
            $message = trans('global.payment_success');
            $this->flash_message('success', $message);
            return redirect('/thank-you?oid='.$orderId);
        } else {
            //payment failed
            $input = $request->all();
            if(isset($input['error']['metadata'])){
                $metadata = $input['error']['metadata'];
                $metadata = json_decode($metadata);
                if(isset($metadata->payment_id)){
                    $objRazorpayDetail = PaymentDetail::where('payment_method_id', 1)->first();
                    $api = new Api($objRazorpayDetail->app_key, $objRazorpayDetail->app_secret);
                    $payment = $api->payment->fetch($metadata->payment_id);

                    $this->orderId = $payment['notes']['merchant_order_id'];
                    $this->paymentMode = $payment['method'];
                    $this->financialStatus = 'failed';
                    $this->store(collect($payment));

                    $order = Order::where('order_nr', $this->orderId)->first();
                    $order->flash_status = 2;
                    $order->save(); 

                    $message = trans('global.payment_failed');
                    $this->flash_message('danger', $message);
                    return redirect('/checkout?status=failed&oid='.$order->id);

                } else {
                    $message = trans('global.payment_failed');
                    $this->flash_message('danger', $message);
                    return redirect('/checkout?status=failed');
                }
            } else {
                $message = trans('global.payment_failed');
                $this->flash_message('danger', $message);
                return redirect('/checkout?status=failed');
            }
        }
    }

    /**
    * Success method after successfully payment made by cashfree Payment Gateway.
    *
    * @return Request
    */
    public function paymentCashFree(Request $request)
    {
        $input = collect($request->all());
        if(isset($input['txStatus']) && $input['txStatus'] == 'SUCCESS'){
            $this->orderId = $input['orderId'];
            $this->paymentMode = $input['paymentMode'];
            $this->financialStatus = 'paid';

            $this->store($input);

            $this->setLiveUserCount('purchase');
            $order = Order::where('order_nr', $this->orderId)->first();
            $order->flash_status = 1;
            $order->save(); 
            $orderId = $order->id;
            $message = trans('global.payment_success');
            $this->flash_message('success', $message);
            return redirect('/thank-you?oid='.$orderId);
        } else {
            $message = trans('global.payment_failed');
            if(isset($input['txMsg'])){
                $message = $input['txMsg'];
            }

            $this->orderId = $input['orderId'];
            $this->paymentMode = $input['paymentMode'];
            $this->financialStatus = 'failed';

            $order = Order::where('order_nr', $this->orderId)->first();
            $order->flash_status = 2;
            $order->save(); 

            $this->store($input);

            $this->flash_message('danger', $message);
            return redirect('/checkout?status=failed&oid='.$order->id);
        }
    }

    /**
    * Success method after successfully payment made by instamojo Payment Gateway.
    *
    * @return Request
    */
    public function paymentInstamojo(Request $request)
    {
        $input = collect($request->all());
        $instamojoDetail = PaymentDetail::where('payment_method_id', 5)->first();
        $isTestMode = "test";
            if(!$instamojoDetail->is_testmode)
            {
                $isTestMode = "api";
            }
        $getPaymentRequest = "https://".$isTestMode.".instamojo.com/v2/payment_requests/";

        $accesstoken = null;
        $cartsData = Helper::getCartUserData();
        foreach($cartsData as $key=>$cart){
            if($cart->accesstoken != null)
            {
                $accesstoken = $cart->accesstoken;
            }
        }

        $client = new \GuzzleHttp\Client();

        $response = $client->get($getPaymentRequest.$input['payment_request_id'].'/', [
              'headers' => [
                'Authorization' => 'Bearer '.$accesstoken,
                'accept' => 'application/json',
              ],
        ]);

        $response =  json_decode($response->getBody(), true);
        $orderId = $response['purpose'];
        
        if(isset($input['payment_status']) && $input['payment_status'] == 'Credit'){
            $this->orderId = $orderId;
            $this->paymentMode = "CARD";
            $this->financialStatus = 'paid';

            $this->store($input);

            $this->setLiveUserCount('purchase');
            $order = Order::where('order_nr', $this->orderId)->first();
            $orderId = $order->id;
            $message = trans('global.payment_success');
            $this->flash_message('success', $message);
            return redirect('/thank-you?oid='.$orderId);
        } else {
            $message = trans('global.payment_failed');
            $this->orderId = $orderId;
            $this->paymentMode = "CARD";
            $this->financialStatus = 'failed';

            $this->store($input);
            $this->flash_message('danger', $message);
            return redirect('/checkout?status=failed');
        }
    }

    /**
    * Success method after successfully payment made by paytm Payment Gateway.
    *
    * @return Request
    */
    public function paymentPaytm(Request $request)
    {

        $input = collect($request->all());
        if(isset($input['STATUS']) && $input['STATUS'] == 'TXN_SUCCESS'){
            $this->orderId = $input['ORDERID'];
            $this->paymentMode = $input['PAYMENTMODE'];
            $this->financialStatus = 'paid';

            $this->store($input);

            $this->setLiveUserCount('purchase');
            $order = Order::where('order_nr', $this->orderId)->first();
            $orderId = $order->id;
            $order->flash_status = 1;
            $order->save(); 
            $message = trans('global.payment_success');
            $this->flash_message('success', $message);
            return redirect('/thank-you?oid='.$orderId);
        } else {
            $message = trans('global.payment_failed');
            if(isset($input['errorMessage'])){
                $message = $input['errorMessage'];
            }
            $this->orderId = $input['ORDERID'];
            if(isset($input['PAYMENTMODE'])){
                $this->paymentMode = $input['PAYMENTMODE'];
            }
            $this->financialStatus = 'failed';

            $order = Order::where('order_nr', $this->orderId)->first();
            $order->flash_status = 2;
            $order->save(); 

            $this->store($input);

            $this->flash_message('danger', $message);
            return redirect('/checkout?status=failed&oid='.$order->id);
        }
    }

     /**
    * Success method after successfully payment made by paytm COD.
    *
    * @return Request
    */
    public function paymentCOD(Request $request)
    {
        $input = collect($request->all());
        try {
            $this->orderId = $input['ORDERID'];
            $this->paymentMode = 'COD';
            $this->financialStatus = 'pending';
            $this->store($input);

            $this->setLiveUserCount('purchase');
            $order = Order::where('order_nr', $this->orderId)->first();
            $orderId = $order->id;
            $order->flash_status = 1;
            $order->save(); 
            $message = trans('global.payment_success');
            $this->flash_message('success', $message);
            $url = '/thank-you?oid='.$orderId;
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.COD_ORDER_SUCCESSFULLY.code'),
                __('constants.messages.COD_ORDER_SUCCESSFULLY.msg'),
                ["url" => $url]
            );

            
        } catch (Exception $e) {

            $message = trans('global.payment_failed');
            $this->orderId = $input['ORDERID'];
            $this->paymentMode = 'COD';
            $this->financialStatus = 'failed';
            $order = Order::where('order_nr', $this->orderId)->first();
            $order->flash_status = 2;
            $order->save(); 
            $this->store($input);
            $this->flash_message('danger', $message);
            $url =  '/checkout?status=failed&oid='.$order->id;
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.COD_PAYMENT_FAILED.code'),
                __('constants.errors.COD_PAYMENT_FAILED.msg'),
                ["url" => $url]
            );
        }
        
    }

    /**
    * empty cart and update order detials after successfull payment and also reduce quantity and manage inventory.
    *
    * @return Response
    */
    public function store($data){
        //update order details
        $order = Order::where('order_nr', $this->orderId)->first();
        $order->fulfillment_status = $this->fulfillmentStatus;
        $order->gateway = $this->paymentMode;
        $order->financial_status = $this->financialStatus;
        $order->paid_at = Carbon::now()->format(config('panel.date_format') . ' ' . config('panel.time_format'));

        if( $this->financialStatus != 'failed'){
            $order->fulfilled_at = Carbon::now()->format(config('panel.date_format') . ' ' . config('panel.time_format'));
        }

        $order->save();
        $orderId = $order->id;
        $userId = $order->user_id;
        $payment_method_id = $order->payment_method_id;

        $payment = new Payment;
        $payment->payment_status = 'pending';
        if($payment_method_id == 1)
        {
            $payment->payment_id = $data['id'];
            $payment->amount = round($data['amount']/100,2);
            if($data['status'] == "captured")
            {
                $payment->payment_status = 'paid';
            }
        }
        elseif($payment_method_id == 2)
        {
            $payment->payment_id = $data['TXNID'];
            $payment->amount = $data['TXNAMOUNT'];
            if($data['RESPCODE'] == "01")
            {
                $payment->payment_status = 'paid';
            }
        }
        elseif($payment_method_id == 3)
        {
            $payment->payment_id = $data['id'];
        }
        elseif($payment_method_id == 4)
        {
            $payment->payment_id = $data['orderId'];
            $payment->amount = $data['orderAmount'];
            if($data['txStatus'] == "SUCCESS")
            {
                $payment->payment_status = 'paid';
            }
        }
        elseif($payment_method_id == 5)
        {
            $payment->payment_id = $data['payment_id'];
            $payment->amount = $order->total;
            if($data['status'] == "Completed")
            {
                $payment->payment_status = 'paid';
            }
        }
        elseif($payment_method_id == 6)
        {
            $payment->payment_id = 'COD';
            $payment->amount = $order->total;
        }
        $payment->order_id = $orderId;
        $payment->data = json_encode($data);
        $payment->save();

        //store shipping and billing address
        $shippingAddress = $this->getShippingAddress($userId);
        $billingAddress = $this->getBillingAddress($userId);
        $shippingId = '';
        $billingId = ''; 
        if(!empty($shippingAddress)){
            $orderShippingAddress = OrderLocation::where(['order_id' => $orderId, 'user_id' => $userId, 'status' => $shippingAddress->status])->first();
            if(!empty($orderShippingAddress))
            {
                $orderShippingAddress = new OrderLocation;
                $orderShippingAddress->order_id = $orderId;
                $orderShippingAddress->user_id = $userId;
                $orderShippingAddress->first_name = $shippingAddress->first_name;
                $orderShippingAddress->last_name = $shippingAddress->last_name;
                $orderShippingAddress->email = $shippingAddress->email;
                $orderShippingAddress->location_name = $shippingAddress->location_name;
                $orderShippingAddress->company_name = $shippingAddress->company_name;
                $orderShippingAddress->address = $shippingAddress->address;
                $orderShippingAddress->address_2 = $shippingAddress->address_2;
                $orderShippingAddress->phone_code = $shippingAddress->phone_code;
                $orderShippingAddress->mobile = $shippingAddress->mobile;
                $orderShippingAddress->status = $shippingAddress->status;
                $orderShippingAddress->postal_code = $shippingAddress->postal_code;
                $orderShippingAddress->country_id = $shippingAddress->country_id;
                $orderShippingAddress->state_id = $shippingAddress->state_id;
                $orderShippingAddress->city_name = $shippingAddress->city_name;
                $orderShippingAddress->save();
            }
            $shippingId = $orderShippingAddress->id;
        }
        if(!empty($billingAddress)){
            $orderBillingAddress = OrderLocation::where(['order_id' => $orderId, 'user_id' => $userId, 'status' => $billingAddress->status])->first();
            if(!empty($orderBillingAddress))
            {
                $orderBillingAddress = new OrderLocation;
                $orderBillingAddress->order_id = $orderId;
                $orderBillingAddress->user_id = $userId;
                $orderBillingAddress->first_name = $billingAddress->first_name;
                $orderBillingAddress->last_name = $billingAddress->last_name;
                $orderBillingAddress->email = $billingAddress->email;
                $orderBillingAddress->location_name = $billingAddress->location_name;
                $orderBillingAddress->company_name = $billingAddress->company_name;
                $orderBillingAddress->address = $billingAddress->address;
                $orderBillingAddress->address_2 = $billingAddress->address_2;
                $orderBillingAddress->phone_code = $billingAddress->phone_code;
                $orderBillingAddress->mobile = $billingAddress->mobile;
                $orderBillingAddress->status = $billingAddress->status;
                $orderBillingAddress->postal_code = $billingAddress->postal_code;
                $orderBillingAddress->country_id = $billingAddress->country_id;
                $orderBillingAddress->state_id = $billingAddress->state_id;
                $orderBillingAddress->city_name = $billingAddress->city_name;
                $orderBillingAddress->save();
            }
            $billingId = $orderBillingAddress->id;
            $order->email = $orderBillingAddress->email;
            $order->mobile = $orderBillingAddress->mobile;
        }
        $order->shipping_address_id = $shippingId;
        $order->billing_address_id = $billingId;
        $order->save();
            $cartdata = Cart::where('mac_id', $this->macAddr)->latest()->get();
            if($userId != null){
                $cartdata = Cart::where('user_id', $userId)->latest()->get();
            } 
            $productDetail = [];
            $user = User::where('id', $userId)->first();
            $email = $user->email;
            $mobile = $user->mobile;
            $fname = $user->name;
            $cardDataIds = [];
            foreach($cartdata as $key => $cart)
            {
                $cardDataIds[] = $cart->id;
                foreach($cart->cart_detail as $key=>$cart_product){
                    //get customer email and mobile details to store in order details table

                    $productId = $cart_product->product_id;
                    $productVariantId = $cart_product->variant_option_id;
                    $quantity = $cart_product->quantity;
                    $product = $cart_product->product;
                    $pname = $product->title;
                    $slug = $product->slug;

                    $productImage = '';                
                    if(isset($product->medias) && $product->medias->count() > 0){
                        $productImage = $product->medias[0]->src;
                    }

                    //get product  details to store in order details table
                    if($productVariantId != ''){
                        $product = $cart_product->variant_options;
                        if(isset($product->variant_media) && $product->variant_media->count() > 0)
                        {
                            $productImage = $product->variant_media[0]->src;
                        }
                    } 

                    $sku = $product->sku;
                    $barcode = $product->barcode;
                    $weight_type_id = $product->weight_type_id;
                    $weight = $product->weight;
                    $hs_code = $product->hs_code;
                    $is_product_charge = $product->is_product_charge;
                    $is_track = $product->is_track;
                    $is_special_product = $product->is_special_product;
                    $special_price = $product->special_price;
                    $cost_per_item = 0;
                    //manage inventory stocks
                    if($productVariantId != ''){
                        //if variant product
                        $InventoryStock = InventoryStock::where('product_id', $productId)->where('product_variant_option_id', $productVariantId)->where('available_quantity', '!=', 0)->get();
                        $variantOption = $cart_product->variant_options;
                        $variantName = $variantOption->variant_name;
                        $productname = $pname.' - '.$variantName;
                        $price = $variantOption->price;
                        $cost_per_item = $variantOption->cost_per_item;
                    } else {
                        //if simple product
                        $InventoryStock = InventoryStock::where('product_id', $productId)->where('available_quantity', '!=', 0)->get();
                        $productname = $product->title;
                        $price = $product->price;
                        $cost_per_item = $product->cost_per_item;
                    }
                    if($InventoryStock->count() > 0){
                        $remainQuantity = $quantity;
                        foreach($InventoryStock as $key=>$inventory){
                            if($remainQuantity > 0){
                                $availableAuantity = $inventory->available_quantity;
                                if($availableAuantity >= $remainQuantity){
                                    $availableAuantity = $availableAuantity - $remainQuantity;
                                    InventoryStock::where('id', $inventory->id)->update(['available_quantity' => $availableAuantity]);
                                    $remainQuantity = 0;
                                } else {
                                    InventoryStock::where('id', $inventory->id)->update(['available_quantity' => 0]);
                                    $remainQuantity = $remainQuantity - $availableAuantity;
                                }
                            }
                        }
                    }
                    //add order details
                    $orderProduct = new OrderProduct;
                    $orderProduct->order_id = $orderId;
                    $orderProduct->product_id = $productId;
                    $orderProduct->user_id = $userId;
                    $orderProduct->email = $order->email;
                    $orderProduct->mobile = $order->mobile;
                    $orderProduct->product_variant_options_id = $productVariantId;
                    $orderProduct->title = $productname;
                    $orderProduct->slug = $slug;
                    $orderProduct->cost_per_item = $cost_per_item;
                    $orderProduct->price = $price;
                    $orderProduct->src = $productImage;
                    $orderProduct->quantity = $quantity;
                    $orderProduct->sku = $sku;
                    $orderProduct->barcode = $barcode;
                    $orderProduct->weight_type_id = $weight_type_id;
                    $orderProduct->weight = $weight;
                    $orderProduct->hs_code = $hs_code;
                    $orderProduct->is_product_charge = $is_product_charge;
                    $orderProduct->is_track = $is_track;
                    $orderProduct->is_special_product = $is_special_product;
                    $orderProduct->special_price = $special_price;
                    $orderProduct->save();
                   
                    //data for email send
                    $productDetail[] = [
                        'product_id' => $productId,
                        'title' => $productname,
                        'slug' => $slug,
                        'price' => $price,
                        'src' => $productImage,
                        'quantity' => $quantity,
                        'sku'   => $sku,
                        'hs_code' => $hs_code,
                    ];
                }
            }
            if( $this->financialStatus != 'failed' ){
                if($email != ''){
                    $data['adminemail'] = config('contactEmail');
                    $data['products'] = $productDetail;
                    $data['fullName'] = $fname;
                    $data['email'] = $email;
                    $data['currencySymbol'] = $order->currency->symbol;
                    $data['subTotal'] = $order->sub_total;
                    $data['shippingCost'] = $order->shipping_cost;
                    $data['taxes'] = $order->taxes;
                    $data['discount'] = $order->discount_amount;
                    $data['total'] = $order->total;
                    $data['orderNumber'] = $order->order_nr;
                    $data['shippingAddress'] = $order->shipping_address;
                    $data['billingAddress'] = $order->billing_address; 
                    $data['orderId'] = $orderId;
                    $data['orderDate'] = $order->paid_at;
                    $data['discountCode'] = $order->discount_code;
                    $data['client_id'] = Config::get('client_id');
                    //send invoice mail
                    $this->emailService->orderConfirmation($data);
                    // $this->emailService->sendAdminInvoiceMail($maildata);
                }

                if($shippingAddress->is_save == 0){
                    Address::where('mac_id', $this->macAddr)->forceDelete();
                }
            }
            if(in_array($this->financialStatus,['paid','pending']))
            {
                //empty cart details
                CartDetail::whereIn('cart_id', $cardDataIds)->forceDelete();
                Cart::whereIn('id',$cardDataIds)->forceDelete();
            }
            else
            {
                $order->discount_code = null;
                $order->discount_amount = 0;
                $order->save();

                $objCart = Cart::where('user_id', $userId)->orWhere('mac_id', $this->macAddr)->first();
                if(!empty($objCart))
                {
                    $objCart->payment_status = ((int) $objCart->payment_status) + 1;
                    $objCart->addresses_id = $billingId;
                    $objCart->shipping_address_id = $shippingId;
                    $objCart->save();
                }
            }
    }

    public function getShippingAddress($userId){
        $address = Address::where('user_id', $userId)->where('status', 0)->where('is_default', 1)->latest()->first();

        return $address;
    }

    public function getBillingAddress($userId){
        $address = Address::where('user_id', $userId)->where('status', 1)->where('is_default', 1)->latest()->first();

        return $address;
    }
}
