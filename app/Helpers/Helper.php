<?php 

namespace App\Helpers;

use Illuminate\Support\Str;
use App\Models\Cart;
use App\Models\PageOption;
use App\Models\AdminSetting;
use App\Models\FrontThemeSetting;
use App\Models\Timing;
use App\Models\StateTax;
use App\Models\ShippingRate;
use App\Models\ShippingDetail;
use App\Models\Weightmanage;
use App\Models\Product;
use App\Models\ProductVariantOption;


use Config;
use Auth;

class Helper
{
  

    public static function applClasses()
    {
        // Demo
        $fullURL = request()->fullurl();
        if (App()->environment() === 'production') {
            for ($i = 1; $i < 7; $i++) {
                $contains = Str::contains($fullURL, 'demo-' . $i);
                if ($contains === true) {
                    $data = config('custom.' . 'demo-' . $i);
                }
            }
        } else {
            $data = config('custom.custom');
        }

        // default data array
        $DefaultData = [
          'mainLayoutType' => 'vertical',
          'theme' => 'light',
          'sidebarCollapsed' => false,
          'navbarColor' => '',
          'horizontalMenuType' => 'floating',
          'verticalMenuNavbarType' => 'floating',
          'footerType' => 'static', //footer
          'layoutWidth' => 'full',
          'showMenu' => true,
          'bodyClass' => '',
          'bodyStyle' => '',
          'pageClass' => '',
          'pageHeader' => true,
          'contentLayout' => 'default',
          'blankPage' => false,
          'defaultLanguage'=>'en',
          'direction' => env('MIX_CONTENT_DIRECTION', 'ltr'),
        ];

        // if any key missing of array from custom.php file it will be merge and set a default value from dataDefault array and store in data variable
        $data = array_merge($DefaultData, $data);

        // All options available in the template
        $allOptions = [
            'mainLayoutType' => array('vertical', 'horizontal'),
            'theme' => array('light' => 'light', 'dark' => 'dark-layout', 'bordered' => 'bordered-layout', 'semi-dark' => 'semi-dark-layout'),
            'sidebarCollapsed' => array(true, false),
            'showMenu' => array(true, false),
            'layoutWidth' => array('full', 'boxed'),
            'navbarColor' => array('bg-primary', 'bg-info', 'bg-warning', 'bg-success', 'bg-danger', 'bg-dark'),
            'horizontalMenuType' => array('floating' => 'navbar-floating', 'static' => 'navbar-static', 'sticky' => 'navbar-sticky'),
            'horizontalMenuClass' => array('static' => '', 'sticky' => 'fixed-top', 'floating' => 'floating-nav'),
            'verticalMenuNavbarType' => array('floating' => 'navbar-floating', 'static' => 'navbar-static', 'sticky' => 'navbar-sticky', 'hidden' => 'navbar-hidden'),
            'navbarClass' => array('floating' => 'floating-nav', 'static' => 'navbar-static-top', 'sticky' => 'fixed-top', 'hidden' => 'd-none'),
            'footerType' => array('static' => 'footer-static', 'sticky' => 'footer-fixed', 'hidden' => 'footer-hidden'),
            'pageHeader' => array(true, false),
            'contentLayout' => array('default', 'content-left-sidebar', 'content-right-sidebar', 'content-detached-left-sidebar', 'content-detached-right-sidebar'),
            'blankPage' => array(false, true),
            'sidebarPositionClass' => array('content-left-sidebar' => 'sidebar-left', 'content-right-sidebar' => 'sidebar-right', 'content-detached-left-sidebar' => 'sidebar-detached sidebar-left', 'content-detached-right-sidebar' => 'sidebar-detached sidebar-right', 'default' => 'default-sidebar-position'),
            'contentsidebarClass' => array('content-left-sidebar' => 'content-right', 'content-right-sidebar' => 'content-left', 'content-detached-left-sidebar' => 'content-detached content-right', 'content-detached-right-sidebar' => 'content-detached content-left', 'default' => 'default-sidebar'),
            'defaultLanguage'=>array('en'=>'en','fr'=>'fr','de'=>'de','pt'=>'pt'),
            'direction' => array('ltr', 'rtl'),
        ];

        //if mainLayoutType value empty or not match with default options in custom.php config file then set a default value
        foreach ($allOptions as $key => $value) {
            if (array_key_exists($key, $DefaultData)) {
                if (gettype($DefaultData[$key]) === gettype($data[$key])) {
                    // data key should be string
                    if (is_string($data[$key])) {
                        // data key should not be empty
                        if (isset($data[$key]) && $data[$key] !== null) {
                            // data key should not be exist inside allOptions array's sub array
                            if (!array_key_exists($data[$key], $value)) {
                                // ensure that passed value should be match with any of allOptions array value
                                $result = array_search($data[$key], $value, 'strict');
                                if (empty($result) && $result !== 0) {
                                    $data[$key] = $DefaultData[$key];
                                }
                            }
                        } else {
                            // if data key not set or
                            $data[$key] = $DefaultData[$key];
                        }
                    }
                } else {
                    $data[$key] = $DefaultData[$key];
                }
            }
        }

        //layout classes
        $layoutClasses = [
            'theme' => $data['theme'],
            'layoutTheme' => $allOptions['theme'][$data['theme']],
            'sidebarCollapsed' => $data['sidebarCollapsed'],
            'showMenu' => $data['showMenu'],
            'layoutWidth' => $data['layoutWidth'],
            'verticalMenuNavbarType' => $allOptions['verticalMenuNavbarType'][$data['verticalMenuNavbarType']],
            'navbarClass' => $allOptions['navbarClass'][$data['verticalMenuNavbarType']],
            'navbarColor' => $data['navbarColor'],
            'horizontalMenuType' => $allOptions['horizontalMenuType'][$data['horizontalMenuType']],
            'horizontalMenuClass' => $allOptions['horizontalMenuClass'][$data['horizontalMenuType']],
            'footerType' => $allOptions['footerType'][$data['footerType']],
            'sidebarClass' => 'menu-expanded',
            'bodyClass' => $data['bodyClass'],
            'bodyStyle' => $data['bodyStyle'],
            'pageClass' => $data['pageClass'],
            'pageHeader' => $data['pageHeader'],
            'blankPage' => $data['blankPage'],
            'blankPageClass' => '',
            'contentLayout' => $data['contentLayout'],
            'sidebarPositionClass' => $allOptions['sidebarPositionClass'][$data['contentLayout']],
            'contentsidebarClass' => $allOptions['contentsidebarClass'][$data['contentLayout']],
            'mainLayoutType' => $data['mainLayoutType'],
            'defaultLanguage'=>$allOptions['defaultLanguage'][$data['defaultLanguage']],
            'direction' => $data['direction'],
        ];
        // set default language if session hasn't locale value the set default language
        if(!session()->has('locale')){
            app()->setLocale($layoutClasses['defaultLanguage']);
        }

        // sidebar Collapsed
        if ($layoutClasses['sidebarCollapsed'] == 'true') {
            $layoutClasses['sidebarClass'] = "menu-collapsed";
        }

        // blank page class
        if ($layoutClasses['blankPage'] == 'true') {
            $layoutClasses['blankPageClass'] = "blank-page";
        }
        // dd($layoutClasses);

        return $layoutClasses;
    }

    public static function updatePageConfig($pageConfigs)
    {
        $demo = 'custom';
        $fullURL = request()->fullurl();
        if (App()->environment() === 'production') {
            for ($i = 1; $i < 7; $i++) {
                $contains = Str::contains($fullURL, 'demo-' . $i);
                if ($contains === true) {
                    $demo = 'demo-' . $i;
                }
            }
        }
        if (isset($pageConfigs)) {
            if (count($pageConfigs) > 0) {
                foreach ($pageConfigs as $config => $val) {
                    Config::set('custom.' . $demo . '.' . $config, $val);
                }
            }
        }
    }

    public static function getMacAddress(){
        $request = request()->input();
        if(env('APP_ENV') == 'local') // windows os
        {
            $mac = substr(exec('getmac'), 0, 17); 
        }
        else
        {
            $mac = $_SERVER['REMOTE_ADDR']; 
        }
        if(isset($request['uniqueId']))
        {
            return  $mac."-".$request['uniqueId'];
        }
        return  $mac;
    }

    public static function getCartUserData()
    {
        $request = request()->input();
        $cartData = [];
        if(Auth::check()){
            $userId = Auth::user()->id;
            $cartData = Cart::where('user_id', $userId)->get();
        } else {
            $macAddr = self::getMacAddress();
            $cartData = Cart::where('mac_id', $macAddr)->get();
        }
        return $cartData;
    }

    public static function getCartTotal($cartsData){
        $total = 0;
        if(!empty($cartsData))
        {
           foreach($cartsData as $key => $singleCart){
               foreach($singleCart->cart_detail as $key => $cartProduct){
                    $pId = $cartProduct->product_id;
                    $cartDetailId = $cartProduct->id;
                    $vOptionId = $cartProduct->variant_option_id;

                    if($vOptionId != ''){ //product is variant product
                        $price = $cartProduct->variant_options->price;
                    } else {//simple product
                        $price = $cartProduct->product->price;
                    }

                    $total = $total + ($price * $cartProduct->quantity);
                }
            }
        }

        return $total;
    }

    public static function getTaxs($cartTotal,$state_id, $shipingAmount=0)
    {
        $finaltaxes = $taxes = 0;
        $objStateTax = StateTax::with('country_tax')->where('state_id', $state_id)->latest()->first();
        if(!empty($objStateTax))
        {
            if($objStateTax->country_tax->include_tax)
            {
                if($objStateTax->country_tax->charge_on_shipping)
                {
                    $cartTotal = $cartTotal + $shipingAmount;
                }

                if($objStateTax->tax_additional == 0)
                {
                    $totaltaxes = $objStateTax->state_tax_percentage + $objStateTax->country_tax->tax_percentage;
                    $taxes = $cartTotal + ($cartTotal*$totaltaxes)/100;

                }
                elseif($objStateTax->tax_additional == 1)
                {
                    $taxes = $cartTotal + ($cartTotal*$objStateTax->state_tax_percentage)/100;
                }
                elseif($objStateTax->tax_additional == 2)
                {
                    $with_country_tax = $cartTotal + ($cartTotal*$objStateTax->country_tax->tax_percentage)/100;
                    $taxes = $with_country_tax + ($with_country_tax*$objStateTax->state_tax_percentage)/100;
                }
                $finaltaxes = $taxes - $cartTotal;
            }
        }
        if($objStateTax->country_tax->round_value)
        {
            return (string) number_format((float)round($finaltaxes), 2, '.', '');
        }

        return (string) number_format((float)$finaltaxes, 2, '.', '');
    }

    public static function getCartWeight($cartsData)
    {
        $weight = $singleProductWeight  = 0;
        foreach($cartsData as $cartKey => $cartsSingleData){
            foreach($cartsSingleData->cart_detail as $cartDetailKey => $cartproduct)
            {   
                $objProduct = Product::select('id','weight','weight_type_id')->whereId($cartproduct->product_id)->latest()->first();
                if($objProduct->weight_type_id != null){
                    $weight_type = Weightmanage::whereId($objProduct->weight_type_id)->first();
                    if(!empty($weight_type))
                    {
                        $singleProductWeight = self::handleWeightManagement($objProduct->weight, $weight_type->short_code, "kg");
                    }
                }
                if($cartproduct->variant_option_id > 0)
                {
                    $objProductVariant = ProductVariantOption::whereId($cartproduct->variant_option_id)->latest()->first();
                    if($objProductVariant->weight > 0 && $objProductVariant->weight_type_id != null)
                    {
                        $weight_type = Weightmanage::whereId($objProductVariant->weight_type_id)->first();
                        if(!empty($weight_type))
                        {
                            $singleProductWeight = self::handleWeightManagement($objProductVariant->weight, $weight_type->short_code, "kg");
                        }
                    }
                }
                   // echo $singleProductWeight."*".$cartproduct->quantity."=".$cartproduct->quantity * $singleProductWeight."<br>";
                    $weight +=  $cartproduct->quantity * $singleProductWeight;
            }
        }
        return $weight;
    }

    public static function calculateRate($main_amount,$weight, $objOrder = []){
        $shippingRateTax = 0;
        $objShippingRatePrice = ShippingRate::where('min','<=', $main_amount)->where('max','>',$main_amount)->where('weight_or_price',1)->latest()->first();
        if(!empty($objShippingRatePrice))
        {
            $shippingRateTax = $objShippingRatePrice->price;
            if(!empty($objOrder))
            {
                self::setShippingRateOrder($objOrder,$objShippingRatePrice);
            }
        }

        if($shippingRateTax == 0)
        {
            $objShippingRateWeight = ShippingRate::where('min','<=', $weight)->where('max','>',$weight)->where('weight_or_price',0)->latest()->first();
            if(!empty($objShippingRateWeight))
            {
                $shippingRateTax = $objShippingRateWeight->price;
                if(!empty($objOrder))
                {
                    self::setShippingRateOrder($objOrder,$objShippingRatePrice);
                }
            }
        }

        if($shippingRateTax == 0)
        {
            $objShippingRate = ShippingRate::where('conditions', 0)->latest()->first();
            if(!empty($objShippingRate))
            {
                $shippingRateTax = $objShippingRate->price;
                if(!empty($objOrder))
                {
                    self::setShippingRateOrder($objOrder,$objShippingRate);
                }
            }
        }
        $objShippingDetail = ShippingDetail::first();
        if(!empty($objShippingDetail))
        {
            if($objShippingDetail->round_value)
            {
                return (string) number_format((float)round($shippingRateTax), 2, '.', '');
            }
        }
        return (string) number_format((float)$shippingRateTax, 2, '.', '');
    }

    public static function setShippingRateOrder($objOrder,$objRateObject)
    {
        $objOrder->shipping_rate_id = $objRateObject->id;
        $objOrder->rate_status = $objRateObject->rate_status;
        $objOrder->conditions = $objRateObject->conditions;
        $objOrder->weight_or_price = $objRateObject->weight_or_price;
        $objOrder->min = $objRateObject->min;
        $objOrder->max = $objRateObject->max;
        $objOrder->save();
    }


    public static function getVoucherCodeAmount($cartData)
    {
        $data = ['voucher_code' => null, 'voucher_amount' => 0];
        foreach($cartData as $cartKey=> $objSingleCart){
            if($objSingleCart->discount_amount > 0)
            {
                $data['voucher_code'] = $objSingleCart->discount_code;
                $data['voucher_amount'] = $objSingleCart->discount_amount;
                break;
            }
        }
        return $data;
    }

    public static function getOption($option_name)
    {
        $pageOption = PageOption::where('option_name', $option_name)->first();
        if($pageOption){
            return $pageOption->option_value;
        }

        return "";
    }

     public static function getAdminIconLogoTitle()
    {
        $data = [];
        $data['admin_icon'] = '/images/logo/favicon.ico';
        $data['admin_logo'] = '/images/logo/logo.png';
        $data['admin_title'] = 'Admin';
        $client_id = Config::get('client_id');
        $objAdminSetting = AdminSetting::where(['client_id'=>$client_id])->first();
        if(!empty($objAdminSetting))
        {
            if($objAdminSetting->icon != null){
                 $data['admin_icon'] = $objAdminSetting->icon;
            }
            if($objAdminSetting->logo != null){
                 $data['admin_logo'] = $objAdminSetting->logo;
            }
            $data['admin_title'] = ($objAdminSetting->title) ?? $data['admin_title'];
        }
        if(Auth::check())
        {
            $authUserId = Auth::user()->id;
            $objUserAdminSetting = AdminSetting::where(['client_id'=>$client_id,'user_id'=>$authUserId])->first();
            if(!empty($objUserAdminSetting))
            {
                if($objAdminSetting->icon != null){
                     $data['admin_icon'] = $objUserAdminSetting->icon;
                }
                if($objAdminSetting->logo != null){
                     $data['admin_logo'] = $objUserAdminSetting->logo;
                }
                $data['admin_title'] = ($objAdminSetting->title) ?? $data['admin_title'];
            }


        }

        return $data;
    }

    public static function getThemeSetting()
    {
        $client_id = Config::get('client_id');
        $objThemeSettings = FrontThemeSetting::where('user_id', $client_id)->first();
        return $objThemeSettings;
    }

    public static function getTiming()
    {
        $client_id = Config::get('client_id');
        $objTimings = Timing::where('user_id', $client_id)->get();
        return $objTimings;
    }

    public static function handleWeightManagement($value, $source, $destination)
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

}
