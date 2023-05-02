<?php 

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Kozz\Laravel\Facades\Guzzle;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Services\EmailService;

use Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\LiveUser;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\ProductMedium;
use App\Models\VariantMedium;
use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\VariantOption;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ExchangeMedium;
use App\Models\ShippingMethod;
use App\Models\Shipments;

use Auth;
use Helper;
use Session;
use File;
use Image;
use Storage;
use PDF;

trait ApiResponser
{
    protected $imageConvert;
    protected $emailService;

    public function __construct()
    {
        $this->imageConvert = false;
        $this->emailService = new EmailService;
    }

    public function successResponse($status, $code,  $message = '', $data = [], $debugMessage = 'Successfull!', $getJsononly = false)
    {   
        if($getJsononly)    
        {    
           return ['msg'=>'success', 'data'=>$data];
        }
        else
        {    
            $responseData = $this->createResponse($data, $status, $code, $message, $debugMessage);
            return response()->json($responseData);
        }

    }

    public function errorResponse($status, $code, $message = '', $debugMessage = 'Failed!', $getJsononly = false)
    {
        if($getJsononly)
        {    
           return ['msg'=>'error'];
        }
        else
        {    
            return response()->json($this->createResponse( [], $status, $code, $message, $debugMessage ));
        }
    }

	public function createResponse($data, $status, $code, $message = '', $debugMessage = '')
    {
        $response = [
            'response' => [
                'status'      => $status,
                'status_code' => intval($code),
                'debug_message' => $debugMessage,
                'message' => $message,
                'data' => $data
            ]
        ];

        return $response;
    }


    // public function getRequestParams($searchKey="")
    // {
    //     $params = [];
    //     if(isset(Request()->params))
    //     {
    //         $params = Request()->params;
    //         $this->isvalidJson($params);
    //         $params = json_decode($params,true);

    //         if(!empty($searchKey))     
    //         {
    //             if(array_key_exists($searchKey, $params))
    //             {
    //                 return $params[$searchKey];
    //             }
    //             else
    //             {
    //                 $msg = __('constants.errors.BACKENDAPI_INVALID_JSON.msg').", ".$searchKey." Param required.";
    //                 throw new HttpException(__('constants.errors.BACKENDAPI_INVALID_JSON.code'),$msg);
    //             }
                
    //         }

    //         return $params;
    //     }  
    // }

    public function validateRequiredParams($requiredField, $paramsField)
    {
        $invalidPrams = array_diff($requiredField, $paramsField);
        if(count($invalidPrams) > 0)
        {
            $msg = __('constants.errors.BACKENDAPI_INVALID_JSON.msg').", ".implode(',', $invalidPrams)." Param required.";
            throw new HttpException(__('constants.errors.BACKENDAPI_INVALID_JSON.code'),$msg);
        }

        return true;
    }

    // public function getHeaderParams($searchKey="",$requied=true)
    // {
        

    //     if(empty(Request()->header('Domain')))
    //     {
    //         $this->domainNotFound();
    //     }


    //     if(!empty($searchKey))
    //     {
    //         if(!empty(Request()->header($searchKey)))
    //         {
    //             return Request()->header($searchKey);
    //         }
    //         else if($requied)
    //         {
    //             $msg = __('constants.errors.BACKENDAPI_INVALID_JSON.msg').", ".$searchKey." Header required.";
    //             throw new HttpException(__('constants.errors.BACKENDAPI_INVALID_JSON.code'),$msg);
    //         }
    //         else
    //         {
    //             return Request()->header($searchKey);
    //         }
            
    //     }

    //     return Request()->header();   
    // }

    public function checkAuthUser()
    {
        $user = [];
        if(Auth::check())
        {
            $user = Auth::user();
            $user->wishlist_count = $user->wishlist->count(); 
        }
        return $user;
    }

    public function createAPIErrorMsg($arrErrorMsg)
    {
        $errorMsg = "";
        if (!empty($arrErrorMsg)) 
        {
            foreach ($arrErrorMsg as $Errkey => $Errvalue) 
            {
                foreach ($Errvalue as $Emsgkey => $Emsgvalue) {

                    $key = $this->getMsgKey($Emsgkey);
                    $errorMsg .= "$key => $Emsgvalue ### ";
                }
            }
        }
        return $errorMsg;
    } 

    public function createMsgFromResponseCode($msg_array){
        $msg = "";
        if (!empty($msg_array)) 
        {
            foreach ($msg_array as $key => $val) 
            {
                foreach ($val as $msgkey => $msgvalue) {

                    $key = $this->getMsgKey($msgkey);
                    $msg .= "$key => $msgvalue";
                }
            }
        }
        return $msg;
    }    

    
    public function isvalidJson($value,$returnType="decode")
    {
        $decoded_data = json_decode($value, true);
        if(json_last_error() != 0)
        {
            $msg = "Invalid Json params, ".json_last_error_msg();
            throw new HttpException(__('constants.errors.BACKENDAPI_INVALID_JSON.code'),$msg);
        }

        return $decoded_data;
    }

    public function displayOurCustomMessage($msg)
    { 
        $response = [
                    'response' => [
                        'status'      => 'success',
                        'status_code' => 500,
                        'message' => $msg,
                    ]
                ];
        echo json_encode($response);
        exit;
    }
    
    public function convertToSlug($text)
    {
        $text = str_slug(strtolower(trim($text)),'-');
        return $text;
    }

    public function userChecklogin($params)
    {
         $user = [];
         $chekingEmailOrMobile = 0;

         if (!is_numeric($params['username'])) {
            $chekingEmailOrMobile = 1;
            $credentials =   ['email' => $params['username'] , 'password' => $params['password'] ];
            if(Auth::attempt($credentials))
            {
                  $user = Auth::user();
            }
         }
         else
         {
            $chekingEmailOrMobile = 2;
            $credentials =   ['mobile' => $params['username'] ,'password' => $params['password'] ];

            if(Auth::attempt($credentials))
            {
                  $user = Auth::user();
            }
         }

        if (!empty($user)) {
          
            if ($user->is_verified == 0) {
                throw new HttpException(
                    __('constants.errors.LOGIN_INACTIVE.code'),
                    __('constants.errors.LOGIN_INACTIVE.msg')
                );
            } else if ($user->blocked == 0) {
                throw new HttpException(
                    __('constants.errors.ACCOUNT_BLOCKED.code'),
                    __('constants.errors.ACCOUNT_BLOCKED.msg')
                );
            }  else {
                $this->setMacToUserId($user->id);
                return true;
            }
        } else {

            if($chekingEmailOrMobile == 1){
                 throw new HttpException(
                    __('constants.errors.EMAIL_RECORDS_NOT_FOUND.code'),
                    __('constants.errors.EMAIL_RECORDS_NOT_FOUND.msg')
                );
            }

            if($chekingEmailOrMobile == 2){
                 throw new HttpException(
                    __('constants.errors.MOBILE_RECORDS_NOT_FOUND.code'),
                    __('constants.errors.MOBILE_RECORDS_NOT_FOUND.msg')
                );
            }

           
        }
    }

    public function setLiveUserCount($setFieldname)
    {
        $fieldName = 'mac_id'; 
        $userId =  Helper::getMacAddress();
         if(Auth::check()){
            $userId = Auth::user()->id;
            $fieldName = 'user_id';
        }
        $cartsData = Cart::where($fieldName, $userId)->get();
        $trackMinute = Config('site.TRACKMINUTE');
        if($cartsData->IsNotEmpty()){
            // check live users in cart
            if($setFieldname == 'checkout')
            {
                LiveUser::where('cart',$userId)->whereBetween('updated_at', [now()->subMinutes($trackMinute), now()])->update(['cart' => 0]);
            }
        }
        $adminIds = User::where('role_id',1)->get()->pluck('id')->toArray();
        if(!in_array($userId, $adminIds))
        {
            $checkLiveUsers = LiveUser::where($setFieldname,$userId)->whereBetween('updated_at', [now()->subMinutes($trackMinute), now()])->first();
            if(empty($checkLiveUsers)){
                $checkLiveUsers = new LiveUser;
            }
            $checkLiveUsers->$setFieldname = $userId;
            $checkLiveUsers->save(); 
        }

        return true;
    }

    public function setMacToUserId($userId)
    {
        $macid = Helper::getMacAddress();
        LiveUser::where('live',$macid)->update(['cart' => $userId]);
        LiveUser::where('cart',$macid)->update(['cart' => $userId]);
        LiveUser::where('checkout',$macid)->update(['checkout' => $userId]);
    }

    public function flash_message($class, $message)
    {
        Session::flash('alert-class', 'alert-'.$class);
        Session::flash('message', $message);
    }

    public function getDifferenceInMinutes($to, $from)
    {
        $to = Carbon::createFromFormat('Y-m-d H:i:s', $to);
        $from = Carbon::createFromFormat('Y-m-d H:i:s',$from);
        $diff_in_minutes = $to->diffInMinutes($from);

        return $diff_in_minutes; 
    }

    public function url_exists($url) {

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($code == 200); // verifica se recebe "status OK"
    }

    public function importImage()
    {

        $productMedias = ProductMedium::where('source', 1)->limit(100)->get();
        if($productMedias->IsNotEmpty())
        {

         $client_id = $productMedias[0]->client_id;
         $this->checkFolder("public/$client_id");
         $this->checkFolder("public/$client_id/images");
            foreach($productMedias as $key => $media){
                if($media->cdn_url != ''){
                    $refrence_id = mt_rand( 1000, 9999);
                    $name = time().$refrence_id.'.png';
                    $image = file_get_contents(public_path('assets/images/no-image.jpg'));
                    if($this->url_exists($media->cdn_url))
                    {   
                        $image = file_get_contents($media->cdn_url);     
                    }
                    $this->checkFolder("public/$client_id/images/$media->product_id");
                    Storage::disk('public')->put("$client_id/images/$media->product_id/$name", $image, 'public');
                    ProductMedium::where('id', $media->id)->update(['source' => 0, 'src' =>  $name]);
                }
            }
        }

        $variantMedias = VariantMedium::where('source', 1)->limit(100)->get();
        if($variantMedias->IsNotEmpty())
        {
            $client_id = $variantMedias[0]->client_id;
             $this->checkFolder("public/$client_id");
             $this->checkFolder("public/$client_id/images");
            foreach($variantMedias as $key => $media){
                if($media->cdn_url != ''){
                    $refrence_id = mt_rand(1000, 9999);
                    $name = time().$refrence_id.'.png';
                    $image = file_get_contents(public_path('assets/images/no-image.jpg'));
                    if($this->url_exists($media->cdn_url))
                    {   
                        $image = file_get_contents($media->cdn_url);     
                    }
                    $this->checkFolder("public/$client_id/images/$media->product_id");
                    Storage::disk('public')->put("$client_id/images/$media->product_id/$name", $image, 'public');
                    VariantMedium::where('id', $media->id)->update(['source' => 0, 'src' =>  $name]);
                }
            }
        }
    }

    public function callImageConvert() // true means some image and false means all image
    {
        $objProductMedias = ProductMedium::where('convert',0)->where('src','!=',null)->limit(100)->get(); 
        if($objProductMedias->IsNotEmpty())
        {
            $client_id = $objProductMedias[0]->client_id;
            foreach($objProductMedias as $key=>$objProductMedia)
            {
                if(file_exists(storage_path("app/public/$client_id/images/$objProductMedia->product_id/$objProductMedia->src")))
                {
                    $this->ConvertImage($objProductMedia->client_id,$objProductMedia->product_id, $objProductMedia->src,$objProductMedia->id,'product');
                }
            }
        }
        //  variants product media

        $objVariantProductMedias = VariantMedium::where('convert',0)->where('src','!=',null)->limit(100)->get(); 

        if($objVariantProductMedias->IsNotEmpty())
        {
            $client_id = $objVariantProductMedias[0]->client_id;
            foreach($objVariantProductMedias as $key=>$objVariantProductMedia)
            {
                if(file_exists(storage_path("app/public/$client_id/images/$objVariantProductMedia->product_id/$objVariantProductMedia->src")))
                {

                    $this->ConvertImage($objVariantProductMedia->client_id,$objVariantProductMedia->product_id, $objVariantProductMedia->src, $objVariantProductMedia->id,'variant');
                }
            }
        }

        if($this->imageConvert)
        {
            $objcheckProductMedias = ProductMedium::where('convert',0)->first(); 
            $objcheckVariantProductMedias = VariantMedium::where('convert',0)->first(); 
            if(empty($objcheckProductMedias) && empty($objcheckVariantProductMedias)){
                $objRolesAdminId = Role::whereIn('title',['admin','Admin'])->get()->pluck('id');
                $objUsers = User::whereIn('role_id',$objRolesAdminId)->where('email','!=',null)->get();
                foreach($objUsers as $key => $objUser)
                {
                    $data = ["fname" => $objUser->name." ".$objUser->last_name, 'email' => $objUser->email];
                    $this->emailService->sendMediaUploadSuccess($data);
                }
            }
        }

    }

    public function ConvertImage($client_id, $product_id, $product_name,$finalId,$source = 'product')
    {

        $arrImageSize =  explode(",", Config::get('site.imagesize')); //['50','400','600'];

        foreach($arrImageSize as $key => $value)
        {
            if(!file_exists(storage_path("app/public/$client_id/images/$product_id/$value/$product_name")))
            {
                $image = Image::make(storage_path("app/public/$client_id/images/$product_id/$product_name"))->resize(null, $value, function($constraint) {
                    $constraint->aspectRatio();
                });
                $this->checkFolder("public/$client_id/images/$product_id/$value/");
                $image->save(storage_path("app/public/$client_id/images/$product_id/$value/$product_name"));
                $this->imageConvert = true;
                if($source == 'product')
                {
                    ProductMedium::whereId($finalId)->update(['convert' => 1]);
                }
                else if($source == 'variant'){
                    VariantMedium::whereId($finalId)->update(['convert' => 1]);
                }
            }
        }
    }

    public function checkFolder($path)
    {
        if(!Storage::exists($path))
        {
            Storage::makeDirectory($path,0777,true);
        }
    }

    public function checkViewFolder($path)
    {
       $dir = resource_path($path);
        if ( !is_dir( $dir ) ) {
            mkdir( $dir, 0777 );       
        }
    }

    public function handleProductSelect($objProducts){
        $data = [];
        $objVariantOptions = VariantOption::get()->pluck('options','id')->toArray();
        foreach($objProducts as $key=>$objProduct){
            $productId = $objProduct->id;
            $title = $objProduct->title;
             $price = $objProduct->original_price;
             $comparePrice = $objProduct->compare_at_price;
             $isContinueSelling = $objProduct->is_continue_selling;
             $maxOrderLimit = $objProduct->max_order_limit;
             $minOrderLimit = $objProduct->min_order_limit;
             $img_src = (!empty($objProduct->medias[0])) ? $objProduct->medias[0]->image_src[2] : '';
           

             if($objProduct->is_product_variant == 1){
                foreach($objProduct->product_variant_options as $key=>$objProductVariantOption){
                    $product_variant_option_id = $objProductVariantOption->id;
                    $addVarintOptions = ($objProductVariantOption->variant_option_1_id != null)?$objVariantOptions[$objProductVariantOption->variant_option_1_id]:'';
                    $addVarintOptions .= ($objProductVariantOption->variant_option_2_id != null)? ",".$objVariantOptions[$objProductVariantOption->variant_option_2_id]:'';
                    $addVarintOptions .= ($objProductVariantOption->variant_option_3_id != null)? ",".$objVariantOptions[$objProductVariantOption->variant_option_3_id]:'';
                    $variantOption = trim($addVarintOptions);
                    $title = $objProduct->title. " (" .$variantOption.")";
                     $price = ($objProductVariantOption->price > 0) ? $objProductVariantOption->price : $price;
                     $comparePrice = ($objProductVariantOption->compare_at_price > 0) ? $objProductVariantOption->compare_at_price : $comparePrice;
                     $img_src = (!empty($objProductVariantOption->variant_media[0])) ? $objProductVariantOption->variant_media[0]->image_src[2] : $img_src;
                    
                     $isContinueSelling = $objProductVariantOption->is_continue_selling;
                     $maxOrderLimit = $objProductVariantOption->max_order_limit;
                     $minOrderLimit = $objProductVariantOption->min_order_limit;
                    array_push($data,['id' => $productId,'product_variant_option_id' => $product_variant_option_id,'title' => $title,'quantity' => 0,'slug'=>$objProduct->slug ,'price' => $price,'img_src'=>$img_src,'sku'=>$objProduct->sku,'barcode'=>$objProduct->barcode,'weight'=>$objProduct->weight,'weight_type_id' => $objProduct->weight_type_id,'length'=>$objProduct->length,'length_type_id'=>$objProduct->length_type_id,'height'=>$objProduct->height,'height_type_id'=>$objProduct->height_type_id,'width'=>$objProduct->width,'width_type_id'=>$objProduct->width_type_id,'hs_code'=>$objProduct->hs_code,'is_product_charge'=>$objProduct->is_product_charge,'is_special_product'=>$objProduct->is_special_product,'special_price'=>$objProduct->special_price,'is_track'=>$objProduct->is_track,'cost_per_item'=>$objProduct->cost_per_item,'total'=>0,'compareprice'=>$comparePrice,'stock_status'=>$objProduct->stock_status, 'isContinueSelling' => $isContinueSelling, 'maxOrderLimit' => $maxOrderLimit, 'minOrderLimit' => $minOrderLimit]);
                }
             }
             else{
                array_push($data,['id'=>$productId,'product_variant_option_id'=>null,'title'=>$title,'quantity'=>0,'slug'=>$objProduct->slug,'price'=>$price,'img_src'=>$img_src,'sku'=>$objProduct->sku,'barcode'=>$objProduct->barcode,'weight'=>$objProduct->weight,'weight_type_id' => $objProduct->weight_type_id,'length'=>$objProduct->length,'length_type_id'=>$objProduct->length_type_id,'height'=>$objProduct->height,'height_type_id'=>$objProduct->height_type_id,'width'=>$objProduct->width,'width_type_id'=>$objProduct->width_type_id,'hs_code'=>$objProduct->hs_code,'is_product_charge'=>$objProduct->is_product_charge,'is_special_product'=>$objProduct->is_special_product,'special_price'=>$objProduct->special_price,'is_track'=>$objProduct->is_track,'cost_per_item'=>$objProduct->cost_per_item,'total'=>0,'compareprice'=>$comparePrice,'stock_status'=>$objProduct->stock_status, 'isContinueSelling' => $isContinueSelling, 'maxOrderLimit' => $maxOrderLimit, 'minOrderLimit' => $minOrderLimit]);
             }
        } 
        return $data;
    }
    

    public function displayValue($intValue, $precision = 1){
        if ($intValue < 900) {
        // 0 - 900
        $n_format = number_format($intValue, $precision);
        $suffix = '';
        } else if ($intValue < 900000) {
            // 0.9k-850k
            $n_format = number_format($intValue / 1000, $precision);
            $suffix = 'K';
        } else if ($intValue < 900000000) {
            // 0.9m-850m
            $n_format = number_format($intValue / 1000000, $precision);
            $suffix = 'M';
        } else if ($intValue < 900000000000) {
            // 0.9b-850b
            $n_format = number_format($intValue / 1000000000, $precision);
            $suffix = 'B';
        } else {
            // 0.9t+
            $n_format = number_format($intValue / 1000000000000, $precision);
            $suffix = 'T';
        }
      // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
      // Intentionally does not affect partials, eg "1.50" -> "1.50"
        if ( $precision > 0 ) {
            $dotzero = '.' . str_repeat( '0', $precision );
            $n_format = str_replace( $dotzero, '', $n_format );
        }
        return $n_format . $suffix;
    }

    public function handleWeightManagement($value, $source, $destination)
    {
        if($source == 'gm' && $destination == "kg")
        {
            $value =  $value / 1000;
        }
        elseif ($source == "lbs" && $destination == "kg") {
            $value =  $value * 0.45359237;
        }
        elseif ($source == "oz" && $destination == "kg") {
            $value =  $value * 0.0283495;
        }    
        return $value;
    }

    public function handleDimensionManagement($value, $source, $destination)
    {
        if($source == "m" && $destination = "cm")
        {
            $value = $value * 100;
        }
        elseif ($source == "mm" && $destination == "cm") {
            $value =  $value * 0.100;
        }
        elseif ($source == "in" && $destination == "cm") {
            $value =  $value * 2.54;
        }    
        elseif ($source == "Yards" && $destination == "cm") {
            $value =  $value * 91.44;
        }    
        return $value;
        
    }

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

    public function getDescriptionData($mainOrderId,$mainOrderProductId,$product_id,$product_variant_options_id)
    {
        $descriptionData = $orderProduct = [];
        $objAllExchangeOrder = OrderProduct::with(['order'])->whereHas('order',function($query) use($mainOrderId){
        $query->where('parent_order_id',$mainOrderId);
        // $query->orWhere('id',$mainOrderId);
        $query->withTrashed();
        })->where(['product_id'=>$product_id,'product_variant_options_id'=>$product_variant_options_id])
        ->withTrashed()
        ->get();
        $objAllExchangeOrderId = $objAllExchangeOrder->pluck('order_id')->toArray();
        $objAllExchangeOrderProduct = OrderProduct::where(['product_id'=>$product_id,'product_variant_options_id'=>$product_variant_options_id])->whereIn('order_id',$objAllExchangeOrderId)->withTrashed()->get();
        $objAllExchangeMedia = ExchangeMedium::whereIn('order_id',$objAllExchangeOrderId)->withTrashed()->get();

       if($objAllExchangeOrderProduct->isNotEmpty())
        {
            foreach($objAllExchangeOrderProduct as $intKey => $allExchangeOrderProduct){
                $descriptionData['client_request'] = $allExchangeOrderProduct->client_request;
                $descriptionData['admin_response'] = $allExchangeOrderProduct->admin_response;
                $descriptionData['exchangeClientQuantity'] = $allExchangeOrderProduct->quantity;
                $descriptionData['exchangeApproveQuantity'] = $allExchangeOrderProduct->admin_approve_quantity;
                $descriptionData['created_at'] = $allExchangeOrderProduct->created_at;
                $descriptionData['updated_at'] = $allExchangeOrderProduct->updated_at;
                $descriptionData['deleted_at'] = $allExchangeOrderProduct->deleted_at;

                $exchangeOrderProductOrderId = $allExchangeOrderProduct->order_id;
                $objSrc = $objAllExchangeMedia->filter(function ($item) use($exchangeOrderProductOrderId) {
                            return $item->order_id == $exchangeOrderProductOrderId;
                        });
                $tempAllMedia = $objAllMediaSrc = [];
                foreach($objSrc as $src){
                    $tempAllMedia['id'] = (!empty($src->src)) ? $src->id : '';
                    $tempAllMedia['img_src'] = (!empty($src->src)) ? $src->src : '';
                    $objAllMediaSrc[] = $tempAllMedia; 
                }
            $descriptionData['img_src'] = $objAllMediaSrc;

            $orderProduct['descriptionData'][] = $descriptionData;
            }
            $orderProduct['descriptionData'] = collect($orderProduct['descriptionData'])->sortBy('created_at')->values()->toArray();
            return $orderProduct['descriptionData'];
        } 
    }

    public function updateDeliveredStatus($shipment_id){
            $status = false;
        try{
            $objShipments = Shipments::where('id',$shipment_id)->first();
            if(!empty($objShipments))
            {
                $objShippingMethod = ShippingMethod::whereId($objShipments->shipping_method_id)->first();
                if($objShippingMethod->title == 'ShipRocket')
                {
                    $objShipments->shipment_staus_id = 2;
                    $objShipments->is_delivered = 1;
                    $objShipments->save();
                    $status = true;
                }
                else if($objShippingMethod->title == 'Ithinklogistics')
                {
                    $objShipments->shipment_staus_id = 'delivered';
                    $objShipments->is_delivered = 1;
                    $objShipments->save();
                    $status = true;
                }
                
            }
        }
        catch (Exception $e) 
        {
            $status = false;
        }
        return $status;
    }

    public function getVariantOptions($variant_option_1,$variant_option_2,$variant_option_3){
        $addVarintOptions = (!empty($variant_option_1)) ? $variant_option_1->options : '';
        $addVarintOptions .= (!empty($variant_option_2)) ? $variant_option_2->options : '';
        $addVarintOptions .= (!empty($variant_option_3)) ? $variant_option_3->options : '';
        $variantOption = trim($addVarintOptions);
        return $variantOption;
    }

    public function orderInvoice($orderId){
        $client_id = Config::get('client_id');
        $data = $this->getOrderData($orderId);
        $fileName = $data['orderNumber'].'-invoice.pdf';
        if(!file_exists(public_path('/storage/'.$client_id.'/invoice/'.$fileName)))
        {
            $pdf = PDF::loadView('client.invoice', compact('data'));
            Storage::put('public/'.$client_id.'/invoice/'.$fileName, $pdf->output());
        }
        $res = ['file_name' => $fileName, 'url' => Config::get('app.url').'/storage/'.$client_id.'/invoice/'];
        return $res;
    }

    public function getOrderData($orderId)
    {
        $data = [];
         $objOrder = Order::with('currency','order_products', 'shipping_address', 'billing_address')->whereId($orderId)->latest()->first();
       if(!empty($objOrder))
       {
            $productDetail = [];
            foreach($objOrder->order_products as $key => $objOrderProduct){
                $productDetail[] = [
                    'product_id' => $objOrderProduct->product_id,
                    'title' => $objOrderProduct->title,
                    'slug' => $objOrderProduct->slug,
                    'price' => $objOrderProduct->price,
                    'quantity' => $objOrderProduct->quantity,
                    'sku'   => $objOrderProduct->sku,
                    'hs_code' => $objOrderProduct->hs_code,
                ];
            }
            $refundProductDetail = [];
            forEach($objOrder->refund_products as $key => $objRefundProduct){
                $refundProductDetail[] = [
                    'refundProductTitle' => $objRefundProduct->title,
                    'refundQuantity' => $objRefundProduct->quantity,
                    'refundPrice' => $objRefundProduct->price,
                    'refundDate' => $objRefundProduct->created_at,
                ];
            }

            $objUser = User::whereId($objOrder->user_id)->first();  

            $data['adminemail'] = config('contactEmail');
            $data['adminContact'] = config('contactNo');
            $data['products'] = $productDetail;
            $data['fullName'] = $objUser->fullname;
            $data['email'] = $objUser->email;
            $data['currencySymbol'] = $objOrder->currency->symbol;
            $data['subTotal'] = $objOrder->sub_total;
            $data['shippingCost'] = $objOrder->shipping_cost;
            $data['taxes'] = $objOrder->taxes;
            $data['discount'] = $objOrder->discount_amount;
            $data['total'] = $objOrder->total;
            $data['orderNumber'] = $objOrder->order_nr;
            $data['shippingAddress'] = $objOrder->shipping_address;
            $data['billingAddress'] = $objOrder->billing_address;
            $data['orderId'] = $orderId;
            $data['orderDate'] = $objOrder->paid_at;
            $data['discountCode'] = $objOrder->discount_code;
            $data['client_id'] = Config::get('client_id');
            $data['refundAmount'] = $objOrder->refund_products->sum('total');
            $data['refundProducts'] = $refundProductDetail;
       }
        return $data;
    }

}