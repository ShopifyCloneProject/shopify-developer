<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\EmailService;
use App\Models\PaymentMethod;
use App\Models\PaymentDetail;
use App\Models\Country;
use App\Models\State;
use App\Models\Order;
use App\Models\OrderLocation;
use App\Models\Address;
use App\Models\CheckoutSetting;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\InventoryStock;
use App\Models\ThemeSetting;
use App\Models\Discount;
use App\Models\DiscountUser;
use App\Models\DiscountProduct;
use App\Models\DiscountCollection;
use App\Models\Product;
use App\Models\ProductVariantOption;
use App\Models\VariantOption;
use App\Models\ProductCollection;
use App\Models\CountryTax;
use App\Models\StateTax;
use App\Models\PaymentMethodCustom;

use Auth;
use Redirect;
use Helper;
use Config;
use PaytmWallet;
use Session;
use paytm\paytmchecksum\PaytmChecksum;
use Exception;
use DateTime;

class CheckoutController extends Controller
{
    private $customerName;
    private $phone;
    private $orderNote;
    private $email;
    private $orderNumber;
    private $cartTotal;
    private $taxesAmount;
    private $shippingAmount;
    private $voucherCode;
    private $voucherAmount;
    private $finalPaymentAmount;
    private $currency;
    private $currencyId;
    private $source = 'WEB';
    private $address;
    private $userId;
    private $paymentMethodId;
    private $macAddr;

    protected $emailService;

    public function __construct(){
        $this->macAddr = Helper::getMacAddress();
        $settings = Config::get('globalSettings');
        $this->currencyId = $settings['ID'];
        $this->currency = $settings['CURRECNY'];
        $this->voucherAmount = 0;
        $this->voucherCode = null;
        $this->emailService = new EmailService;
    }

    public function index()
    {   
        $settings = CheckoutSetting::all()->pluck('data','title');
        $user = $this->checkAuthUser();
        $status = '';
        if(isset($_REQUEST['status'])){
            $status = $_REQUEST['status'];
        }
        if(isset($_REQUEST['oid'])){
            $oid = $_REQUEST['oid'];
            $order = Order::where('id', $oid)->first();
            if($order->flash_status == 1)
            {
                $message = trans('global.payment_success');
                $this->flash_message('success', $message);
            }
            if($order->flash_status == 2)
            {
                $message = trans('global.payment_failed');
                $this->flash_message('danger', $message);
            }
            $order->flash_status = 0;
            $order->save();
        }
        
        //get active payment methods
        $shippingAddresses = [];
        $billingAddresses = [];
        $indexShipping = 0;
        $indexBilling = 0;

        $countries = Country::all()->pluck('name', 'id');
        $states = $states = State::where('country_id', Config::get('DEFAULT_COUNTRY'))->pluck('name', 'id'); //default india. need to do it dynamically
        $addresses = [];

        if(!empty($user)){
            $addresses = $user->addresses;
        } else{
           // $addresses = Address::where('mac_id', $this->macAddr)->get();
        }

        foreach ($addresses as $key => $address) {
            if($address->status == 0){
               $addresstype = 'shippingAddresses';
               $ind = $indexShipping++;
            } else {
               $addresstype = 'billingAddresses';
               $ind = $indexBilling++;
            }

            $$addresstype[$ind]['id'] =  $address->id;
            $$addresstype[$ind]['first_name'] =  $address->first_name;
            $$addresstype[$ind]['last_name'] =  $address->last_name;
            $$addresstype[$ind]['address'] =  $address->address;
            $$addresstype[$ind]['address_2'] =  $address->address_2;
            $$addresstype[$ind]['email'] =  $address->email;
            $$addresstype[$ind]['mobile'] =  $address->mobile;
            $$addresstype[$ind]['city_name'] =  $address->city_name;
            $$addresstype[$ind]['country_id'] =  $address->country_id;
            $$addresstype[$ind]['Shortcode'] =  ($address->country) ? $address->country->short_code : '';
            $$addresstype[$ind]['state_id'] =  $address->state_id;
            $$addresstype[$ind]['Statename'] = ($address->state) ? $address->state->name : '';
            $$addresstype[$ind]['postal_code'] =  $address->postal_code;
            $$addresstype[$ind]['is_default'] =  $address->is_default;
            $$addresstype[$ind]['phone_code'] =  ($address->country) ? $address->country->phone_code : '';
            $$addresstype[$ind]['status'] =  $address->status;
            $$addresstype[$ind]['isSaveAddress'] =  $address->is_save == 1 ? true : false;
            $$addresstype[$ind]['allStates'] = State::where('country_id', $address->country_id)->pluck('name', 'id');
        }
        $paymentMethods = PaymentMethod::select('id', 'title', 'status')
        ->whereHas('details', function($query){
            $query = $query->where('status', 1);
        })->where('status', 1)
        ->get();

        $client_id = Config::get('client_id');
        $objPaytmPaymentDetail = PaymentDetail::where(['user_id' => $client_id, 'payment_method_id' => 2])->first();
        $paytmurlmode = "securegw-stage";
        if(!empty($objPaytmPaymentDetail))
        {
            if($objPaytmPaymentDetail->is_testmode == 0)
            {
                $paytmurlmode = "securegw";
            }
        }
        
        $paytmurl = "https://".$paytmurlmode.".paytm.in/theia/processTransaction";

        $objCashfreePaymentDetail = PaymentDetail::where(['user_id' => $client_id, 'payment_method_id' => 4])->first();
        $cashfreeurlmode = "test";
         if(!empty($objCashfreePaymentDetail))
         {
            if($objCashfreePaymentDetail->is_testmode == 0)
            {
                $cashfreeurlmode = "api";
            }
         }

        $cashfreeurl = "https://".$cashfreeurlmode.".cashfree.com/billpay/checkout/post/submit";
        $data = [
            'page' => 'checkout',
            'paymentMethods' => $paymentMethods,
            'user'        => $user,
            'countries' => $countries,
            'states' => $states,
            'shippingAddresses' => $shippingAddresses,
            'billingAddresses' => $billingAddresses,
            'settings' => $settings,
            'paymentstatus' => $status,
            'paytmurl' => $paytmurl,
            'cashfreeurl' => $cashfreeurl,
        ];

        if(false){

        }
        else{
            return view('theme.default.pages.checkout', compact('data'));
        }
    }

    /**
    * Redirect the user to the Payment Gateway.
    *
    * @return Response
    */
    public function processCashfree(Request $request){
        try{
            
            //check product is in stock or not while checkout
            $outOfStockProducts = $this->getOutOfStockProducts();

            if(!empty($outOfStockProducts)){
                $message = 'Sorry, we do not have enough '.$outOfStockProducts[0]['name'].' in stock to fulfill your order ('.$outOfStockProducts[0]['quantity'].' available). We apologize for any inconvenience caused.';
                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.OUT_OF_STOCK.code'),
                    $message,
                );
            }

            $params = collect($request->all());
            $user = $this->getUserDetails();

            $this->setOrderDetails($user, $params['orderNote']);
            $cashFreeDetail = PaymentDetail::where('payment_method_id', 4)->first();
            $secretKey = $cashFreeDetail->app_secret;
            $appKey = $cashFreeDetail->app_key;
            $this->paymentMethodId = 4;
            $this->createOrder();
            $postData = array(
              "appId" => $appKey,
              "orderId" => $this->orderNumber,
              "orderAmount" => $this->finalPaymentAmount,
              "orderCurrency" => $this->currency,
              "orderNote" => $this->orderNote,
              "customerName" => $this->customerName,
              "customerEmail" => $this->email,
              "customerPhone" => $this->phone,
              "returnUrl" => Config::get('CASHFREE_RETURN_URL'),
              "notifyUrl" => Config::get('CASHFREE_NOTIFY_URL'),
            );
            // get secret key from your config
            ksort($postData);

            $signatureData = "";
            foreach ($postData as $key => $value){
                $signatureData .= $key.$value;
            }
            $signature = hash_hmac('sha256', $signatureData, $secretKey,true);
            $signature = base64_encode($signature);

            $postData['signature'] = $signature;
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.SIGNATURE_GET_SUCCESSFULLY.code'),
                __('constants.messages.SIGNATURE_GET_SUCCESSFULLY.msg'),
                $postData
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }
   
    /**
    * Redirect the user to the Payment Gateway.
    *
    * @return Response
    */
    public function processRazorpay(Request $request){
        try{
            //check product is in stock or not while checkout
            $outOfStockProducts = $this->getOutOfStockProducts();

            if(!empty($outOfStockProducts)){
                $message = 'Sorry, we do not have enough '.$outOfStockProducts[0]['name'].' in stock to fulfill your order ('.$outOfStockProducts[0]['quantity'].' available). We apologize for any inconvenience caused.';
                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.OUT_OF_STOCK.code'),
                    $message,
                );
            }

            $params = collect($request->all());
            $user = $this->getUserDetails();

            $this->setOrderDetails($user, $params['orderNote']);
            $imageUrl = "https://cdn.razorpay.com/logos/FFATTsJeURNMxx_medium.png";
            $objThemeLogo = ThemeSetting::where(['sectionname' => 'header', 'page' => 1])->first();
            if(!empty($objThemeLogo))
            {
                if(isset($objThemeLogo->logo[0]))
                {
                    $imageUrl = Config::get('app.url').$objThemeLogo->logo[0]['imageurl'];
                }
            }

            $cashFreeDetail = PaymentDetail::where('payment_method_id', 1)->first();
            $secretKey = $cashFreeDetail->app_secret;

            $this->paymentMethodId = 1;
            $this->createOrder();
            $finalPaymentAmount = 0;
            $currency = Config::get('globalSettings')['CURRECNY'];
            if(is_numeric( $this->finalPaymentAmount ) && floor( $this->finalPaymentAmount ) != $this->finalPaymentAmount)
            {
                list($amount1,$amount2) = explode(".", $this->finalPaymentAmount);
                $finalPaymentAmount = $amount1.$amount2;
                if(strlen($amount2) == 1)
                {
                    $finalPaymentAmount = $finalPaymentAmount*10;
                }
                
            }
            else
            {
                $finalPaymentAmount = $this->finalPaymentAmount * 100;
            }

            $razorpayOptions = [
                "key"               => $cashFreeDetail->app_key,
                "amount"            => $finalPaymentAmount,
                "currency"          => $currency,
                "captured"          => true,
                "name"              => Config::get('app.name'),
                "description"       => "Buy best price",
                "image"             => $imageUrl,
                "callback_url"      =>  Config::get('RAZORPAY_CALLBACK_URL'),
                "redirect"          => true,
                "prefill"           => [
                    "name"              => $this->customerName,
                    "email"             => $this->email,
                    "contact"           => $this->phone,
                ],
                "notes"             => [
                    "address"           => $this->address->id,
                    "merchant_order_id" => $this->orderNumber,
                ],
                "theme"             => [
                    "color"             => "#17c6aa"
                ],
            ];
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.SIGNATURE_GET_SUCCESSFULLY.code'),
                __('constants.messages.SIGNATURE_GET_SUCCESSFULLY.msg'),
                $razorpayOptions
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    /**
    * Redirect the user to the Payment Gateway.
    *
    * @return Response
    */
    public function processInstamojo(Request $request)
    {
        try{
            //check product is in stock or not while checkout
            $outOfStockProducts = $this->getOutOfStockProducts();

            if(!empty($outOfStockProducts)){
                $message = 'Sorry, we do not have enough '.$outOfStockProducts[0]['name'].' in stock to fulfill your order ('.$outOfStockProducts[0]['quantity'].' available). We apologize for any inconvenience caused.';
                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.OUT_OF_STOCK.code'),
                    $message,
                );
            }

            $params = collect($request->all());
            $user = $this->getUserDetails();
            $this->setOrderDetails($user, $params['orderNote']);
            $this->paymentMethodId = 5;
            $this->createOrder();

            $instamojoDetail = PaymentDetail::where('payment_method_id', 5)->first();
            $isTestMode = "test";
            if(!$instamojoDetail->is_testmode)
            {
                $isTestMode = "api";
            }

            $generateTokenRequest = "https://".$isTestMode.".instamojo.com/oauth2/token/";
            $generatePaymentRequest = "https://".$isTestMode.".instamojo.com/v2/payment_requests/";

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

            $cartData = Helper::getCartUserData();
            foreach($cartData as $key=>$cart){
                $cart->accesstoken = $finalAccessToken['access_token'];
                $cart->save();
            }

            $payload = [
                'purpose' => $this->orderNumber,
                'amount' => $this->finalPaymentAmount,
                'phone' => $this->phone,
                'buyer_name' => $this->customerName,
                'redirect_url' => Config::get('INSTAMOJO_CALLBACK_URL')."?uniqueId=".$request['uniqueId'],
                'send_email' => true,
                'send_sms' => true,
                'email' => $this->email,
                'allow_repeated_payments' => false,
                'webhook' => Config::get('INSTAMOJO_WEBHOOK'),
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
            Config::set('order_id', $this->orderNumber);

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.SIGNATURE_GET_SUCCESSFULLY.code'),
                __('constants.messages.SIGNATURE_GET_SUCCESSFULLY.msg'),
                $response
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    /**
    * Redirect the user to the Payment Gateway.
    *
    * @return Response
    */
    public function processPaytm(Request $request)
    {
        try{
             //check product is in stock or not while checkout
            $outOfStockProducts = $this->getOutOfStockProducts();

            if(!empty($outOfStockProducts)){
                $message = 'Sorry, we do not have enough '.$outOfStockProducts[0]['name'].' in stock to fulfill your order ('.$outOfStockProducts[0]['quantity'].' available). We apologize for any inconvenience caused.';
                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.OUT_OF_STOCK.code'),
                    $message,
                );
            }

            $params = collect($request->all());
            $user = $this->getUserDetails();
            $this->setOrderDetails($user, $params['orderNote']);
            $this->paymentMethodId = 2;
            $this->createOrder();

            $paytmDetails = PaymentDetail::where('payment_method_id', 2)->first();
            $paytmParams["REQUEST_TYPE"] = 'DEFAULT';
            $paytmParams["MID"] = $paytmDetails->app_key;
            $paytmParams["ORDER_ID"] = $this->orderNumber;
            $paytmParams["CUST_ID"] = $this->userId;
            $paytmParams["INDUSTRY_TYPE_ID"] = $paytmDetails->industry_type;
            $paytmParams["CHANNEL_ID"] = 'WEB';
            $paytmParams["TXN_AMOUNT"] = (string)$this->finalPaymentAmount;
            $paytmParams["WEBSITE"] = $paytmDetails->website;
            $paytmParams["CALLBACK_URL"] = Config::get('PAYTM_CALLBACK_URL');
            $paytmParams["MOBILE_NO"] = $this->phone;
            $paytmParams["EMAIL"] = $this->email;
            $paytmChecksum = PaytmChecksum::generateSignature($paytmParams, $paytmDetails->app_secret);
            $paytmParams['CHECKSUMHASH'] = $paytmChecksum;

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.SIGNATURE_GET_SUCCESSFULLY.code'),
                __('constants.messages.SIGNATURE_GET_SUCCESSFULLY.msg'),
                $paytmParams
             );
        } catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function processCOD(Request $request){
        try{
            $params = collect($request->all());
             //check product is in stock or not while checkout
            $outOfStockProducts = $this->getOutOfStockProducts();
            if(!empty($outOfStockProducts)){
                $message = 'Sorry, we do not have enough '.$outOfStockProducts[0]['name'].' in stock to fulfill your order ('.$outOfStockProducts[0]['quantity'].' available). We apologize for any inconvenience caused.';
                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.OUT_OF_STOCK.code'),
                    $message,
                );
            }

            $user = $this->getUserDetails();
            $this->setOrderDetails($user, $params['orderNote']);
            $this->paymentMethodId = 6;
            $this->createOrder();
            $data['ORDERID'] = $this->orderNumber;
            $data['MACID'] = $this->macAddr;
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.ORDER_CREATED_FOR_COD.code'),
                __('constants.messages.ORDER_CREATED_FOR_COD.msg'),
                $data
            );
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

    public function getOutOfStockProducts(){
        $orderProductDetails = $this->getCartData();
        $outOfStockProducts = [];
        if(!empty($orderProductDetails)){
            $count = 0;
            foreach($orderProductDetails as $key=>$product){
                $continueSelling = $product['continueSelling'];
                if(!$continueSelling){
                    $cartquantity = $product['cartquantity'];
                    $quantity = $product['quantity'];
                    if($cartquantity > $quantity){
                        $outOfStockProducts[$count]['name'] = $product['productName'];
                        $outOfStockProducts[$count]['quantity'] = $product['quantity'];
                        $count++;
                    }
                }
            }
        }

        return $outOfStockProducts;
    }

    public function getUserDetails(){
        $user = $this->checkAuthUser();
        if(empty($user)){
            //if guest checkout then get email and phone details from address table
            $email = null;
            $phone = null;
            $macAddr = $this->macAddr;
            $isCreateUser = false;
            $checkExistUser = null;
            $address = Address::select('first_name', 'last_name', 'email', 'mobile')->where('mac_id', $macAddr)->where('status', 1)->latest()->first();

            if(!empty($address)){
                $email = $address->email;
                $phone = $address->mobile;
                if($email != '' && $phone != ''){
                    //check if email exist in user table
                    $checkExistUser = User::where('email', $email)->first();
                    if(empty($checkExistUser)){
                        //if email exist then check for phone
                        $checkExistUser = User::where('mobile', $phone)->first();
                        if(empty($checkExistUser)){
                            $isCreateUser = true;
                        }
                    }
                } 
                elseif ($phone != '') {
                    //if email not exist then check for phone
                    $checkExistUser = User::where('mobile', $phone)->first();
                    if(empty($checkExistUser)){
                        $isCreateUser = true;
                    }
                } 
                elseif ($email != '') {
                    //if email not exist then check for phone
                    $checkExistUser = User::where('email', $email)->first();
                    if(empty($checkExistUser)){
                        $isCreateUser = true;
                    }
                }
            }

            if($isCreateUser){
                $user = new User;
                $user->name = $address->first_name;
                $user->last_name = $address->last_name;
                $user->email = $address->email;
                $user->mobile = $address->mobile;
                $user->role_id = 3; // 3 for client
                $user->save();
                $userId = $user->id;

                $shippingaddress = Address::where('mac_id', $macAddr)->where('status', 0)->latest()->first();
                if(!empty($shippingaddress)){
                    $shippingaddress->user_id = $userId;
                    $shippingaddress->save();
                }

                $billingaddress = Address::where('mac_id', $macAddr)->where('status', 1)->latest()->first();
                if(!empty($billingaddress)){
                    $billingaddress->user_id = $userId;
                    $billingaddress->save();
                }
                $cartsData = Cart::where('mac_id', $macAddr)->latest()->first();
                $cartsData->user_id = $userId;
                $cartsData->save(); 
                return $user;
            } else {
                $email1 = $checkExistUser->email;
                $phone1 = $checkExistUser->mobile;
                //update email if not exist
                if($email1 == ''){
                    $checkExistUser->email = $email;
                    $checkExistUser->save();
                } 
                //update phone if not exist
                if($phone1 == ''){
                    $checkExistUser->mobile = $phone;
                    $checkExistUser->save();
                }
                if(!Auth::check())
                {
                    $shippingaddress = Address::where('mac_id', $macAddr)->where('status', 0)->latest()->first();
                    if(!empty($shippingaddress)){
                        $shippingaddress->user_id = $checkExistUser->id;
                        $shippingaddress->save();
                    }

                    $billingaddress = Address::where('mac_id', $macAddr)->where('status', 1)->latest()->first();
                    if(!empty($billingaddress)){
                        $billingaddress->user_id = $checkExistUser->id;
                        $billingaddress->save();
                    }                       
                }

                $cartsData = Cart::where('mac_id', $macAddr)->latest()->first();
                $cartsData->user_id = $checkExistUser->id;
                $cartsData->save(); 

                return $checkExistUser;
            }
        }

        return $user;
    }

    public function setOrderDetails($user, $note){
        $userId = NULL;
        $email = NULL;

        if(!empty($user)){
            $shippingAddress = $this->getShippingAddress();
            $billingAddress = $this->getBillingAddress();
            $userId = $user->id;
            $email = $user->email;
        }
        $this->userId =  $userId;
        $this->customerName =  $shippingAddress->first_name.' '. $shippingAddress->last_name;
        $this->phone =  $shippingAddress->mobile;
        $this->orderNote =  $note;
        $this->email = $email;
        $cartsData = Helper::getCartUserData();
        $this->orderNumber = $this->getOrderNumber();
        $this->cartTotal = Helper::getCartTotal($cartsData);
        $this->address = $shippingAddress;
        $weight = Helper::getCartWeight($cartsData);
        $this->shippingAmount = Helper::calculateRate($this->cartTotal,$weight);
        $this->taxesAmount = Helper::getTaxs($this->cartTotal, $shippingAddress->state_id, $this->shippingAmount);
        if($cartsData->IsNotEmpty())
        {   
            $voucherData = Helper::getVoucherCodeAmount($cartsData);
            $this->voucherCode = $voucherData['voucher_code'];
            $this->voucherAmount = $voucherData['voucher_amount'];
        }
        $this->finalPaymentAmount = round(($this->cartTotal + $this->taxesAmount + $this->shippingAmount) - $this->voucherAmount,2);
    }

    public function createOrder(){
        $objState = StateTax::with('country_tax')->where(['country_taxes_id' => $this->address->country_id, 'state_id' => $this->address->state_id])->latest()->first();
        $objOrder = new Order;
        $objOrder->user_id = $this->userId;
        $objOrder->order_nr = $this->orderNumber;
        $objOrder->currency_id = $this->currencyId;
        $objOrder->payment_method_id = $this->paymentMethodId;
        $objOrder->sub_total = $this->cartTotal;
        $objOrder->taxes = $this->taxesAmount;
        $objOrder->shipping_cost = $this->shippingAmount;
        $objOrder->discount_code = $this->voucherCode;
        $objOrder->discount_amount = $this->voucherAmount;
        $objOrder->total = ($this->cartTotal + $this->taxesAmount + $this->shippingAmount) - $this->voucherAmount;
        $objOrder->source = $this->source;
        $objOrder->fulfillment_status = 'unfulfilled';
        $objOrder->note = $this->orderNote;
        $objOrder->financial_status = 'failed';
        $objOrder->status = 'open';
        $objOrder->country_tax_percentage = $objState->country_tax->tax_percentage;
        $objOrder->state_tax_percentage = $objState->state_tax_percentage;
        $objOrder->state_text = $objState->text;
        $objOrder->state_tax_additional = $objState->tax_additional;
        $objOrder->save();
        $this->setWithOrderLocation($objOrder);
        Helper::calculateRate($objOrder->sub_total,0,$objOrder);
    }

    /**
    * return last order number.
    *
    * @return Response
    */
    public function getOrderNumber(){
        $latestOrder = Order::orderBy('order_nr','DESC')->withTrashed()->first();
        if(!empty($latestOrder)){
            $orderNumber = $latestOrder->order_nr;
            $orderNumber = $orderNumber + 1;
        } else {
            $orderNumber = Config::get('ORDER_START_NUMBER');
        }

        return $orderNumber;
    }

    public function getStates($id)
    {
        try{
            $states = State::where('country_id', $id)->pluck('name', 'id');
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.STATES_GET_SUCCESSFULLY.code'),
                __('constants.messages.STATES_GET_SUCCESSFULLY.msg'),
                $states
            );
         } catch (Exception $e) {
             return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public  function countCheckout()
    {
        try {
            $this->setLiveUserCount('checkout');
            $data = [];
            $data['shippingAddresses'] = [];
            $data['billingAddresses'] = [];

            if(!Auth::check())
            {
                $objShippingAddress = Address::where(['mac_id' => $this->macAddr, 'store_status' => 0, 'status' => 0])->latest()->first();
                if(!empty($objShippingAddress))
                {
                    $data['shippingAddresses'] = $objShippingAddress;
                    $data['shippingAddresses']['allStates'] = State::where('country_id', $objShippingAddress->country_id)->pluck('name', 'id');
                }

                $objBilingAddress = Address::where(['mac_id' => $this->macAddr, 'store_status' => 0, 'status' => 1])->latest()->first();
                if(!empty($objBilingAddress))
                {
                    $data['billingAddresses'] = $objBilingAddress;
                    $data['billingAddresses']['allStates'] = State::where('country_id', $objBilingAddress->country_id)->pluck('name', 'id');
                }
            }
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.LIVE_CART_COUNT_SUCCESFULLY.code'),
                __('constants.messages.LIVE_CART_COUNT_SUCCESFULLY.msg'),
                $data
            );
            
        } catch (Exception $e) {
             return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function addEditAddress(Request $request)
    {
       try{
            $params = collect($request->all());
            // $required = ['firstName', 'lastName'];
            // $this->validateRequiredParams($required,$params->keys()->toArray());

            //user auth detail
            $user = $this->checkAuthUser();
            $userId = null;
            $macAddr = null;
            if(!empty($user)){
                $userId = $user->id;
            } else {
                $macAddr = $this->macAddr;
            }

            //set default user first name and last name if not available while add or edit address
            $firstName = $params['first_name'] != '' ? $params['first_name'] : $user->name;
            $lastName = $params['last_name'] != '' ? $params['last_name'] : $user->last_name;
            $email = $params['email'];
            $phone = $params['mobile'];
            $address1 = $params['address'];
            $address2 = isset($params['address_2']) ? $params['address_2'] : '';
            $city = $params['city_name'];
            $country = $params['country_id'];
            $state = $params['state_id'];
            $pincode = $params['postal_code'];
            $status = $params['status'];
            $is_default =  1; //check default address already set or not
            $isSaveAddress = (isset($params['is_save']) && $params['is_save']) ? 1 : 0;

            if(!Auth::check())
            {
                $objUseraddresses = Address::where(['email'=>$email,'status' => $status])->orWhere('mobile', $phone)->get();
                if($objUseraddresses->IsNotEmpty())
                {
                    foreach($objUseraddresses as $key => $objSingleAddress)
                    {
                        if($objSingleAddress['address'] == $address1 && $objSingleAddress['address_2'] == $address1 && $objSingleAddress['city_name'] == $city && $objSingleAddress['state_id'] == $state && 
                            $objSingleAddress['country_id'] == $country && $objSingleAddress['postal_code'] == $pincode)
                        {
                             return $this->successResponse(
                                __('constants.SUCCESS_STATUS'),
                                __('constants.messages.ADDRESS_ADDED_SUCCESFULLY.code'),
                                __('constants.messages.ADDRESS_ADDED_SUCCESFULLY.msg'),
                            );
                        }
                    }
                }
            }

            $phone_code = Country::whereId($country)->first()->phone_code;

            if(isset($params['id'])){ // check for address id
                //reset all default value
                $address = Address::where('id', $params['id'])->first();
            } else {
                $address = new Address;
                if($userId != null){
                    Address::where('user_id', $userId)->where('status', $status)->update(['is_default' => 0]);
                }
            }
            
            $address->user_id = $userId;
            $address->mac_id = $macAddr;
            $address->first_name = $firstName;
            $address->last_name = $lastName;
            $address->status = $status;
            $address->address = $address1;
            $address->address_2 = $address2;
            $address->email = $email;
            $address->phone_code = $phone_code;
            $address->mobile = $phone;
            $address->country_id = $country;
            $address->state_id = $state;
            $address->city_name = $city;
            $address->postal_code = $pincode;
            $address->is_default = $is_default; //1=default, 0=not default
            $address->is_save = $isSaveAddress;
            $address->save();

            if(isset($params['id'])){
                //edit recored
                 return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.ADDRESS_UPDATED_SUCCESFULLY.code'),
                    __('constants.messages.ADDRESS_UPDATED_SUCCESFULLY.msg'),
                );
            } else {
                //add new record block
                $addresses['id'] =  $address->id;
                $addresses['first_name'] =  $address->first_name;
                $addresses['last_name'] =  $address->last_name;
                $addresses['address'] =  $address->address;
                $addresses['address_2'] =  $address->address_2;
                $addresses['email'] =  $address->email;
                $addresses['mobile'] =  $address->mobile;
                $addresses['city_name'] =  $address->city_name;
                $addresses['country_id'] =  $address->country_id;
                $addresses['Shortcode'] =  ($address->country) ? $address->country->short_code : '';
                $addresses['state_id'] =  $address->state_id;
                $addresses['Statename'] = ($address->state) ? $address->state->name : '';
                $addresses['postal_code'] =  $address->postal_code;
                $addresses['is_default'] =  $address->is_default;
                $addresses['phone_code'] =  ($address->country) ? $address->country->phone_code : '';
                $addresses['status'] =  $address->status;
                $addresses['isSaveAddress'] =  $address->is_save == 1 ? true : false;
                $addresses['allStates'] = State::where('country_id', $address->country_id)->pluck('name', 'id');

                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.ADDRESS_ADDED_SUCCESFULLY.code'),
                    __('constants.messages.ADDRESS_ADDED_SUCCESFULLY.msg'),
                    $addresses
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

    public function ChangeDafaultAddress(Request $request)
    {
       try{
            $params = collect($request->all());
            $id = $params['id'];
            $status = $params['status'];
            if(Auth::check())
            {
                $user = $this->checkAuthUser();
                $userId = $user->id;
                Address::where('user_id', $userId)->where('status', $status)->update(['is_default' => 0]);
                $address = Address::where('id', $id)->first();
                $address->is_default = 1; //1=default, 0=not default
                $address->save();
            }
            else
            {
                $mac_id = $this->macAddr;
                Address::where('mac_id', $mac_id)->where('status', $status)->update(['is_default' => 0]);
                $address = Address::where('id', $id)->first();
                $address->is_default = 1; //1=default, 0=not default
                $address->save();

            }

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.ADDRESS_UPDATED_SUCCESFULLY.code'),
                __('constants.messages.ADDRESS_UPDATED_SUCCESFULLY.msg'),
            );

        } catch (Exception $e) {
             return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function getShippingAddress(){
        $user = $this->checkAuthUser();
        if(!empty($user)){
            $address = Address::where('user_id', $user->id)->where('status', 0)->where('is_default', 1)->latest()->first();
        } else {
            $address = Address::where('mac_id', $this->macAddr)->where('status', 0)->where('is_default', 1)->latest()->first();
        }
        return $address;
    }

    public function getBillingAddress(){
        $user = $this->checkAuthUser();
        if(!empty($user)){
            $address = Address::where('user_id', $user->id)->where('status', 1)->where('is_default', 1)->latest()->first();
        } else {
            $address = Address::where('mac_id', $this->macAddr)->where('status', 1)->where('is_default', 1)->latest()->first();
        }
        return $address;
    }

    public function getCartData()
    {
        $user = $this->checkAuthUser();
            $cartData = [];
        if(!empty($user)){
            $userId = $user->id;
            $vOptionId = '';
        } 
        $cartData = Helper::getCartUserData();
        if($cartData->isNotEmpty())
        {
            foreach($cartData as $key=>$cartsSingleData){
                foreach($cartsSingleData->cart_detail as $key=>$cartproduct){
                    //check product is variant product or not

                    if(isset($cartproduct)){
                        $pId = $cartproduct->product_id;
                        $cartDetailId = $cartproduct->id;
                        $vOptionId = $cartproduct->variant_option_id;
                        $productname = '';
                        $stockStatus = false;
                        $quantity = 0;
                        $continueSelling = 1;

                        if($vOptionId != ''){ //product is variant product
                            $product = $cartproduct->product;
                            $variantOption = $cartproduct->variant_options;
                            $quantity = $cartproduct->quantity;
                            $variantName = $variantOption->variant_name;
                            $productname = $product->title.' - '.$variantName;
                            $stockStatus = $variantOption->stock_status;
                            $quantity = $variantOption->quantity;
                            $continueSelling = $variantOption->is_continue_selling;

                            $productComparePrice = 0;
                            $price = $variantOption->price;
                            if(isset($variantOption->compare_at_price) && $variantOption->compare_at_price != 0){
                                $productComparePrice = $variantOption->compare_at_price;
                            }
                        } else {//simple product
                            $product = $cartproduct->product;
                            $variantOption = $cartproduct->variant_options;
                            $quantity = $cartproduct->quantity;
                            $productname = $product->title;
                            $stockStatus = $product->stock_status;
                            $quantity = $product->quantity;
                            $continueSelling = $product->is_continue_selling;

                            $productComparePrice = 0;
                            $price = $product->price;
                            if(isset($product->compare_at_price) && $product->compare_at_price != 0){
                                $productComparePrice = $product->compare_at_price;
                            }
                        }

                        $cartData[$key] = [
                            'id' => $cartproduct->id,
                            'productId' => $pId,
                            'variantOptionId' => $vOptionId,
                            'productName' => $productname,
                            'cartquantity' => $cartproduct->quantity,
                            'cartId' => $cartproduct->cart_id,
                            'productPrice' => $price,
                            'productComparePrice' => $productComparePrice,
                            'stock_status' => $stockStatus, 
                            'slug' => $product->slug,
                            'quantity' => $quantity,
                            'continueSelling' => $continueSelling,
                        ];
                    }
                }
            }
        }
        
        return $cartData;
    }

    public function setWithOrderLocation($objOrder)
    {
        $orderId = $objOrder->id;
        $userId = $this->userId;
        $shippingAddress = $this->getShippingAddress($userId);
        $billingAddress = $this->getBillingAddress($userId);
        $shippingId = '';
        $billingId = ''; 
        if(!empty($shippingAddress)){
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
            $shippingId = $orderShippingAddress->id;
        }
        if(!empty($billingAddress)){
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
            $billingId = $orderBillingAddress->id;
            $objOrder->email = $orderBillingAddress->email;
            $objOrder->mobile = $orderBillingAddress->mobile;
        }
        $objOrder->shipping_address_id = $shippingId;
        $objOrder->billing_address_id = $billingId;
        $objOrder->save();
    }

    public function checkVoucher(Request $request){
        try
        {
            $status = 0;
            $voucherAmount = 0;
            $params = collect($request->all());
            $required = ['amount', 'couponcode'];
            $this->validateRequiredParams($required,$params->keys()->toArray());
            $objDiscount = Discount::where('code',$params['couponcode'])->first();
            if(!empty($objDiscount))
            {
                if($objDiscount->status)
                {
                    $currency_id = config('globalSettings')['ID'];
        
                    if($objDiscount->currency_id == $currency_id)
                    {
                        if($objDiscount->initial_value <= $params['amount'])
                        {
                            if($objDiscount->starting_date <= date('Y-m-d H:i:s'))
                            {
                                if($objDiscount->expiry_type)
                                {
                                    if($objDiscount->expiry_date >= date('Y-m-d H:i:s'))
                                    {
                                        $status = $this->checkUser($objDiscount);
                                    }
                                    else
                                    {
                                        $status = 104;
                                    }
                                }
                                else
                                {
                                    $status = $this->checkUser($objDiscount);
                                }
                            }
                            else
                            {
                                $status = 102;
                            }
                        }
                        else
                        {   
                            $status = 101;
                        }
                    }
                    else
                    {
                        $status = 110;
                    }
                }
                if($status == 1)
                {
                    $cartsData = Helper::getCartUserData();
                    if($objDiscount->percentage_or_amount) //1 for percentage
                    {
                        $voucherAmount = round($params['amount'] * $objDiscount->amount / 100);
                    }
                    else // 0 for amount
                    {
                        $voucherAmount = $objDiscount->amount;
                    }
                    foreach($cartsData as $key=>$cart){
                        $cart->discount_code = $objDiscount->code;
                        $cart->discount_amount = $voucherAmount;
                        $cart->save();
                    }

                    return $this->successResponse(
                        __('constants.SUCCESS_STATUS'),
                        __('constants.messages.VALID_VOUCHER_CODE.code'),
                        __('constants.messages.VALID_VOUCHER_CODE.msg'),
                        $voucherAmount
                    );
                }
            }

            if(in_array($status,[0,102,103,107,108,109,110]))
            {
                return $this->errorResponse(
                    __('constants.ERROR_STATUS'),
                    __('constants.errors.INVALID_VOUCHER_CODE.code'),
                    __('constants.errors.INVALID_VOUCHER_CODE.msg'),
                    $status
                );
            }
            else if($status == 101)
            {
                return $this->errorResponse(
                    __('constants.ERROR_STATUS'),
                    __('constants.errors.INVALID_AMOUNT_VOUCHER_CODE.code'),
                    __('constants.errors.INVALID_AMOUNT_VOUCHER_CODE.msg'),
                    $status
                );
            }
            else if($status == 104)
            {
                return $this->errorResponse(
                    __('constants.ERROR_STATUS'),
                    __('constants.errors.VOUCHER_CODE_EXPIRED.code'),
                    __('constants.errors.VOUCHER_CODE_EXPIRED.msg'),
                    $status
                );
            }
            else if($status == 105)
            {
                return $this->errorResponse(
                    __('constants.ERROR_STATUS'),
                    __('constants.errors.VOUCHER_CODE_LIMIT_EXCEED.code'),
                    __('constants.errors.VOUCHER_CODE_LIMIT_EXCEED.msg'),
                    $status
                );
            }
            else if($status == 106)
            {
                return $this->errorResponse(
                    __('constants.ERROR_STATUS'),
                    __('constants.errors.VOUCHER_CODE_NOT_AUTHORIZE.code'),
                    __('constants.errors.VOUCHER_CODE_NOT_AUTHORIZE.msg'),
                    $status
                );
            }
        }
        catch (Exception $e) 
        {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }
    public function checkUser($objDiscount){

        if(Auth::check())
        {
            $authUser = $this->checkAuthUser(); 
            $objDiscountUser = DiscountUser::where(['discount_id'=>$objDiscount->id,'user_id'=>$authUser->id])->first();
            if(!empty($objDiscountUser)){ // login user with particular discount
                    $objUsedDiscountCodeCount = Order::where(['user_id' => $authUser->id, 'discount_code' => $objDiscount->code])->count();
                    if($objUsedDiscountCodeCount <= $objDiscount->user_availability)
                    {
                        $status = $this->checkCart($objDiscount,$authUser->id, "user");
                    }
                    else
                    {
                        $status = 105;
                    }
            }
            else
            {   
                // login user with all discount
                $status = $this->checkCart($objDiscount, $authUser->id, "user");
            }
        }
        else
        { 

            $objDiscountUserAll = DiscountUser::where(['discount_id'=>$objDiscount->id])->latest()->first();
            if(!empty($objDiscountUserAll)){
                   $status = 106;
            }
            else
            {
                $mac = $this->macAddr;
                $status = $this->checkCart($objDiscount, $mac, "mac");

            }
        }
        return $status;
    }

    public function checkCart($objDiscount, $user_id_or_mac, $type){
        $objCartId = [];
        $status = 108;
        if($type == 'user')
        {
            $objCart = Cart::where('user_id',$user_id_or_mac)->latest()->get();
        }
        else
        {
            $objCart = Cart::where('mac_id',$user_id_or_mac)->latest()->get();
        }
        if(!empty($objCart))
        {
            $objCartIds = $objCart->pluck('id')->toArray();
            $objCartDetails = CartDetail::select('product_id')->whereIn('cart_id',$objCartIds)->get();
            if(!empty($objCartDetails)){
                $status = $this->checkDiscountProduct($objDiscount, $objCartDetails);
            }
            else
            {
                $status = 107;
            }
        }
        return $status;
    }

    public function checkDiscountProduct($objDiscount, $objCartDetails){
        $status = 109;
        $objCartDetailsProductId = $objCartDetails->pluck('product_id')->toArray();
        if($objDiscount->product_or_collection)
        {
            if($objDiscount->product_status)
            {
                return 1;
            }
            else
            {
                $objDiscountProduct = DiscountProduct::where('discount_id', $objDiscount->id)->latest()->first();
                if(!empty($objDiscountProduct))
                {
                    $objDiscountParticularProduct = DiscountProduct::where('discount_id', $objDiscount->id)->whereIn('product_id',$objCartDetailsProductId)->where('status',1)->latest()->first();
                    if(!empty($objDiscountParticularProduct))
                    {
                        return 1;
                    }
                }
            }
        }
        else
        {
            $objDiscountCollection = DiscountCollection::where(['discount_id' =>  $objDiscount->id, 'status' => 1])->latest()->get();
            $collectionId = $objDiscountCollection->pluck('collection_id')->toArray();
            $objProductCollection = ProductCollection::whereIn('product_id',$objCartDetailsProductId)->whereIn('collection_id',$collectionId)->distinct('collection_id')->first(); 
            if(!empty($objProductCollection))
            {
                return 1;
            }
        }
        return $status;
    }

     public function clearVoucher(Request $request){
        try{
            $params = collect($request->all());
            $cartData = Helper::getCartUserData();
            foreach($cartData as $singleCart){
                $singleCart->discount_code = null;
                $singleCart->discount_amount = 0;
                $singleCart->save();
            }
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.DISCOUNT_CODE_CLEARED_SUCCESSFULLY.code'),
                __('constants.messages.DISCOUNT_CODE_CLEARED_SUCCESSFULLY.msg'),
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
     }

     public function abandonedCheckout()
     {
        $data = $tempData = $mainData = [];
        $cart_id = 111;
        $checkoutUrl = Route('checkout');
        $cartData = Helper::getCartUserData();

        if($cartData->isNotEmpty())
        {
            $paymentStatus = $cartData->where('payment_status',1)->pluck('payment_status')->toArray();
            if(!empty($paymentStatus)){

            foreach($cartData as $cartdata){
                    $objCartDetailsProductId = $cartdata->cart_detail->pluck('product_id')->toArray();
                    $objCartDetailsProductVariantId = $cartdata->cart_detail->pluck('variant_option_id')->toArray();
                    $email = $cartdata->user->email;
                }
            $objSelectionProducts = Product::select('id','title','quantity','price','is_product_variant')
                ->with([
                    'medias' => function($media){
                        $media->select('client_id','product_id','src'); 
                    }, 
                    'product_variant_options' => function ($variant) use($objCartDetailsProductVariantId) {
                        $variant->select('id','product_id','variant_option_1_id','variant_option_2_id','variant_option_3_id','price');
                        $variant->whereIn('id', $objCartDetailsProductVariantId);
                    }, 
                    'product_variant_options.variant_media' => function ($variantmedia) {
                        $variantmedia->select('client_id','product_variant_id','product_id','src'); 
                    },
                    'product_variant_options.variant_option_1','product_variant_options.variant_option_2','product_variant_options.variant_option_3'
                    ])->whereIn('id',$objCartDetailsProductId)
                    ->get();

            foreach($objSelectionProducts as $objSelectionProduct)
            {
                if($objSelectionProduct->is_product_variant == 0)
                {
                    $tempData['title'] = $objSelectionProduct->title;
                    $tempData['img_src'] = (!empty($objSelectionProduct->medias[0])) ? config::get('app.url').$objSelectionProduct->medias[0]->image_src[2] : '';
                    $tempData['price'] = $objSelectionProduct->price;

                    $mainData[] = $tempData;
                }

                if($objSelectionProduct->is_product_variant)
                {
                    if($objSelectionProduct->product_variant_options->isNotEmpty())
                    {
                        foreach($objSelectionProduct->product_variant_options as $objProductVariantOptions)
                        {   
                            $variantOption = $this->getVariantOptions($objProductVariantOptions->variant_option_1,$objProductVariantOptions->variant_option_2,$objProductVariantOptions->variant_option_3);
                                    $tempData['title'] = $objSelectionProduct->title. " (" .$variantOption.")";
                                    $tempData['img_src'] = (!empty($objProductVariantOptions->variant_media[0])) ? config::get('app.url').$objProductVariantOptions->variant_media[0]->image_src[2] : config::get('app.url').$objSelectionProduct->medias[0]->image_src[2];
                                    $tempData['price'] = $objProductVariantOptions->price;

                            $mainData[] = $tempData;
                        }
                    }
                }
            }

            $data = [
                'adminContact' => config('contactNo'),
                'adminemail' => config('contactEmail'),
                'mainData' => $mainData,
                'checkoutUrl' => $checkoutUrl,
                'email' => $email
                ];

            $this->emailService->abandonedCheckout($data);
            dd('Mail Sent');
            }
        }
     }

     public function paymentError(){
        $data = [];
        $cartData = Helper::getCartUserData();
        $checkoutUrl = Route('checkout');

            if($cartData->isNotEmpty()){
                $paymentStatus = $cartData->where('payment_status',1)->pluck('payment_status')->toArray();
                if(!empty($paymentStatus)){
                    $orderId = '0c6de670-a910-11ed-a2ea-d9d150134359';
                    $orderDetails = Order::with('user')->
                    with('user', function($userQuery){
                        $userQuery->select('id','name','email');
                    })->select('id','order_nr','user_id','total')->latest()->first();

                    $data = [
                    'adminContact' => config('contactNo'),
                    'adminemail' => config('contactEmail'),
                    'orderDetails' => $orderDetails,
                    'email' => $orderDetails->user->email,
                    'checkoutUrl' => $checkoutUrl,
                    ];

                $this->emailService->paymentError($data);
                dd('Payment error Mail Sent');
                }
            }
     }

     public function pendingPaymentError(){
        $orderId = '13702380-a929-11ed-a189-bf4933ba9e84';
        $data = $shippingAddress = [];
        $totalAmount = 0;
        $orderDetails = Order::with('user')->
                    with('user', function($userQuery){
                        $userQuery->select('id','name','email');
                    })->select('id','order_nr','user_id','taxes','total','discount_amount','shipping_cost','created_at','paid_at')->where('id',$orderId)->latest()->first();

        if(!empty($orderDetails)){
            $totalAmount = $orderDetails->sub_total + $orderDetails->taxes + $orderDetails->shipping_cost - $orderDetails->discount_amount; 
            $orderPaidAt = explode(" ",$orderDetails->paid_at);
        }

        $data = [
            'adminemail' => config('contactEmail'),
            'adminContact' => config('contactNo'),
            'orderDetails' => $orderDetails,
            'email' => $orderDetails->user->email,  
            'totalAmount' => $totalAmount,  
            'orderPaidAt' => $orderPaidAt[0],  
        ];

        $this->emailService->pendingpaymentError($data);
        dd('Pending Payment error Mail Sent');
     }

     public function pendingPaymentSuccess(){
        $orderId = '13702380-a929-11ed-a189-bf4933ba9e84';
        $paidByCustomer = 0;
        $data = $shippingAddress = $tempOrderProduct = $mainOrderProduct = [];
        $orderDetails = Order::with('user','shipping_address','order_products')->
                    with([
                    'user'=> function($userQuery){
                        $userQuery->select('id','name','email');
                    },
                    'shipping_address' => function($addressQuery){
                        $addressQuery->select('id','first_name','last_name','address','address_2','city_name','state_id','country_id','postal_code','mobile');
                    },
                    'order_products' => function($orderProductQuery){
                        $orderProductQuery->select('id','order_id','product_id','product_variant_options_id','src','title','quantity','price');
                    },
                ])->select('id','order_nr','user_id','taxes','sub_total','discount_amount','shipping_cost','shipping_address_id','created_at')->where('id',$orderId)->latest()->first();

        $paidByCustomer = $orderDetails->sub_total + $orderDetails->shipping_cost + $orderDetails->taxes - $orderDetails->discount_amount;

        if(!empty($orderDetails)){
            // $tempOrderProduct['trackUrl'] = null;
            $date = date_create($orderDetails->created_at);
            $orderCreated = date_format($date,'d F, Y');
            if($orderDetails->order_products->isNotEmpty()){
                foreach($orderDetails->order_products as $orderProduct){
                    $tempOrderProduct['image_src'] = config::get('app.url').$orderProduct->image_src[2];
                    $tempOrderProduct['title'] = $orderProduct->title;
                    $tempOrderProduct['quantity'] = $orderProduct->quantity;
                    $tempOrderProduct['price'] = $orderProduct->price;

                    if($orderProduct->order_id == $orderId){
                        $tempOrderProduct['trackUrl'] = Route('orderdata',['order_id'=>$orderId,'order_product_id'=>$orderProduct->id]);
                    }
                    $mainOrderProduct[] = $tempOrderProduct;
                }
            }
            if(!empty($orderDetails->shipping_address)){
                $shippingAddress = [
                    'first_name' => $orderDetails->shipping_address->first_name,
                    'last_name' => $orderDetails->shipping_address->last_name,
                    'address' => $orderDetails->shipping_address->address,
                    'address_2' => $orderDetails->shipping_address->address_2,
                    'city_name' => $orderDetails->shipping_address->city_name,
                    'state' => $orderDetails->shipping_address->state,
                    'country' => $orderDetails->shipping_address->short_code,
                    'postal_code' => $orderDetails->shipping_address->postal_code,
                    'mobile' => $orderDetails->shipping_address->mobile,
                ];
            }
        }

        $data = [
            'email' => $orderDetails->user->email,  
            'orderDetails' => $orderDetails,
            'orderCreated' => $orderCreated,  
            'shippingAddress' => $shippingAddress,  
            'orderProducts' => $mainOrderProduct,  
            'paidByCustomer' => $paidByCustomer,  
        ];
        // dd($data);
        // dd($data['orderDetails']['order_products']);

        $this->emailService->pendingpaymentSuccess($data);
        dd('Pending Payment Success Mail Sent');
     }
}
