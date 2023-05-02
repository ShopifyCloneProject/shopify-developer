<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Fbpixel;
use App\Models\UserStore;
use App\Models\MainMenu;
use App\Models\ThemeSetting;
use App\Models\CustomSetting;
use App\Models\Domain;
use App\Models\AdminSetting;
use Config;
use View;
use Auth;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    { 
        $this->domainSettings();

       if(!Config::has('pixeldata') && \Schema::hasTable('fbpixels')){
            $objFbPixel = Fbpixel::where('status',1)->get();
            Config::set('pixeldata', $objFbPixel);
       }
        View::share('pixeldata', Config::get('pixeldata'));
        
        $id = env('DEFAULT_ID');
        $currency = env('DEFAULT_CURRECNY');
        $currencysymbol = env('DEFAULT_CURRECNY_SYMBOL');
        $defaultcountry = env('DEFAULT_COUNTRY');
        $contactEmail =  env('CONTACT_EMAIL');
        $senderEmail =  env('SENDER_EMAIL');
        $contactNo = "";

        if(\Schema::hasTable('currencies')){
            $objUserStore = UserStore::select('id', 'currency_id', 'country_id', 'store_contact_email', 'sender_email','mobile')->with(['currency' => function($qry){
                $qry->select('id', 'name', 'currency', 'symbol');
            }])->whereHas('currency')->first();
            if($objUserStore){
                $id = $objUserStore->currency->id;
                $currency = $objUserStore->currency->currency;
                $currencysymbol = $objUserStore->currency->symbol;
                $contactEmail = $objUserStore->store_contact_email;
                $contactNo = $objUserStore->mobile;
                $senderEmail = $objUserStore->sender_email;
                $defaultcountry = $objUserStore->country_id;
            } 
        }

        Config::set('contactEmail', $contactEmail);
        Config::set('senderEmail', $senderEmail);
        Config::set('contactNo', $contactNo);
        View::share('contactEmail', $contactEmail);
        View::share('senderEmail', $senderEmail); 
        View::share('contactNo', $contactNo); 


       $objMenuData = [];
         if(!Config::has('menudata') && \Schema::hasTable('mainmenu')){
            $objMenuData = MainMenu::with('allrelations')->where('level',1)->orderBy('order')->get()->toArray();
        }
       
        $strLogo = config('app.url').'/images/logo/logo.png';
        $objThemePages = [];
         if(!Config::has("globalSettings['strLogo']") && \Schema::hasTable('themepages')){
            $objThemePages = ThemeSetting::where('page', 1)->select('id','page','sectionname','logo','status')->orderBy('order')->get()->toArray();
            if(isset($objThemePages[0]['logo'][0])){
                $strLogo = $objThemePages[0]['logo'][0]['imageurl'];
            }
        }


        $globalSettings = [
            'ID' => $id,
            'CURRECNY' => $currency,
            'CURRECNY_SYMBOL' => $currencysymbol,
            'DEFAULT_COUNTRY' => $defaultcountry,
            'strLogo'    => $strLogo,
            'themePages' => $objThemePages,
            'client_id' => Config::get('client_id'), 
        ];

        Config::set('globalSettings', $globalSettings);
        View::share('globalSettings',$globalSettings);
        View::share('client_id', Config::get('client_id'));
        View::share('menudata',$objMenuData);

        
    
        $headStyle = '';
        $bodyStyle = '';
        if(\Schema::hasTable('custom_settings')){
            $customSetting = CustomSetting::all()->pluck('value', 'type');
            if(!empty($customSetting)){
                $headStyle = isset($customSetting['head_script']) ? $customSetting['head_script'] : '' ;
                $bodyStyle = isset($customSetting['body_script']) ? $customSetting['body_script'] : '' ;
            }
        }

        View::share('head_style', $headStyle);
        View::share('body_style', $bodyStyle); 
        View::share('client_id',Config::get('client_id')); 

        if(\Schema::hasTable('themepages')){
            $icontitledata = $this->getGeneralIconLogoTitle(1,'header');
            View::share('icontitledata',$icontitledata);
        }
       


    }

    public function domainSettings(){
        Config::set('CALL_APP_URL', env('CALL_APP_URL'));
        Config::set('CALL_FRONT_APP_URL', env('CALL_FRONT_APP_URL'));
        Config::set('CASHFREE_RETURN_URL', env('CASHFREE_RETURN_URL'));
        Config::set('CASHFREE_NOTIFY_URL', env('CASHFREE_NOTIFY_URL'));   
        Config::set('RAZORPAY_CALLBACK_URL', env('RAZORPAY_CALLBACK_URL'));   
        Config::set('INSTAMOJO_CALLBACK_URL', env('INSTAMOJO_CALLBACK_URL'));   
        Config::set('PAYTM_CALLBACK_URL', env('PAYTM_CALLBACK_URL'));   
        Config::set('INSTAMOJO_PAYMENT_REQUEST', env('INSTAMOJO_PAYMENT_REQUEST'));   
        Config::set('INSTAMOJO_WEBHOOK', env('INSTAMOJO_WEBHOOK'));   
        Config::set('DEFAULT_COUNTRY', env('DEFAULT_COUNTRY'));  
        Config::set('DEFAULT_CURRECNY', env('DEFAULT_CURRECNY'));  
        Config::set('ORDER_START_NUMBER', env('ORDER_START_NUMBER'));  
        Config::set('DISPLAY_ORDER_LIMIT', env('DISPLAY_ORDER_LIMIT'));  
        Config::set('PER_PAGE', env('PER_PAGE'));  
        Config::set('SEARCH_USER_LIMIT', env('SEARCH_USER_LIMIT'));  
        Config::set('SEARCH_PRODUCT_LIMIT', env('SEARCH_PRODUCT_LIMIT'));  
        Config::set('client_id', env('CLIENT_ID')); 
        Config::set('shipment_order_number', env('SHIPMENT_ORDER_START_NUMBER')); 
        Config::set('return_shipment_order_number', env('RETURN_SHIPMENT_ORDER_START_NUMBER')); 
        Config::set('ithinkcountry', env('ITHINKCOUNTRY')); 
        Config::set('display_review_length', env('DISPLAY_REVIEW_LENGTH')); 

      if(\Schema::hasTable('domains')){
           
            $objDomains = Domain::where('user_id',env('CLIENT_ID'))->first();
            if(!empty($objDomains))
            {    if($objDomains->appname != null){
                    Config::set('app.name', $objDomains->appname);    
                }
                if($objDomains->appurl != null){
                    Config::set('app.url', $objDomains->appurl);    
                }
                if($objDomains->authurl != null){
                    Config::set('sanctum.stateful', explode(',', $objDomains->authurl));    
                }
                if($objDomains->call_app_url != null){
                    Config::set('CALL_APP_URL', $objDomains->call_app_url);    
                }
                if($objDomains->front_app_url != null){
                    Config::set('CALL_FRONT_APP_URL', $objDomains->front_app_url);    
                }
                if($objDomains->db_connection != null){
                    Config::set('database.default', $objDomains->db_connection);    
                }
                if($objDomains->db_host != null){
                    Config::set('database.connections.mysql.host', $objDomains->db_host);    
                }
                if($objDomains->db_port != null){
                    Config::set('database.connections.mysql.host', $objDomains->db_port);    
                }
                if($objDomains->db_database != null){
                    Config::set('database.connections.mysql.database', $objDomains->db_database);    
                }
                if($objDomains->db_username != null){
                    Config::set('database.connections.mysql.username', $objDomains->db_username);    
                }
                if($objDomains->db_password != null){
                    Config::set('database.connections.mysql.password', $objDomains->db_password);    
                }
                if($objDomains->mail_mailer != null){
                    Config::set('mail.default', $objDomains->mail_mailer);    
                }
                if($objDomains->mail_host != null){
                    Config::set('mail.mailers.smtp.host', $objDomains->mail_host);    
                }
                if($objDomains->mail_port != null){
                    Config::set('mail.mailers.smtp.port', $objDomains->mail_port);    
                }
                if($objDomains->mail_username != null){
                    Config::set('mail.mailers.smtp.username', $objDomains->mail_username);    
                }
                if($objDomains->mail_password != null){
                    Config::set('mail.mailers.smtp.password', $objDomains->mail_password);    
                }
                if($objDomains->mail_encryption != null){
                    Config::set('mail.mailers.smtp.encryption', $objDomains->mail_encryption);    
                }
                if($objDomains->mail_from_address != null){
                    Config::set('mail.from.address', $objDomains->mail_from_address);    
                }
                if($objDomains->mail_from_name != null){
                    Config::set('mail.from.name', $objDomains->mail_from_name);    
                }
                if($objDomains->cashfree_return_url != null){
                    Config::set('CASHFREE_RETURN_URL', $objDomains->cashfree_return_url);    
                }
                if($objDomains->cashfree_notify_url != null){
                    Config::set('CASHFREE_NOTIFY_URL', $objDomains->cashfree_notify_url);    
                }
                if($objDomains->razorpay_callback_url != null){
                    Config::set('RAZORPAY_CALLBACK_URL', $objDomains->razorpay_callback_url);    
                }
                if($objDomains->instamojo_callback_url != null){
                    Config::set('INSTAMOJO_CALLBACK_URL', $objDomains->instamojo_callback_url);    
                }
                if($objDomains->paytm_callback_url != null){
                    Config::set('PAYTM_CALLBACK_URL', $objDomains->paytm_callback_url);    
                }
                if($objDomains->instamojo_payment_request != null){
                    Config::set('INSTAMOJO_PAYMENT_REQUEST', $objDomains->instamojo_payment_request);    
                }
                if($objDomains->instamojo_webhook != null){
                    Config::set('INSTAMOJO_WEBHOOK', $objDomains->instamojo_webhook);    
                }
                if($objDomains->default_country != null){
                    Config::set('DEFAULT_COUNTRY', $objDomains->default_country);    
                }
                if($objDomains->default_currency != null){
                    Config::set('DEFAULT_CURRECNY', $objDomains->default_currency);    
                }
                if($objDomains->image_size != null){
                    Config::set('site.imagesize', $objDomains->image_size);    
                }
                if($objDomains->order_start_number != null){
                    Config::set('ORDER_START_NUMBER', $objDomains->order_start_number);    
                }
                if($objDomains->display_order_limit != null){
                    Config::set('DISPLAY_ORDER_LIMIT', $objDomains->display_order_limit);    
                }
                if($objDomains->per_page != null){
                    Config::set('PER_PAGE', $objDomains->per_page);    
                }
                if($objDomains->search_user_limit != null){
                    Config::set('SEARCH_USER_LIMIT', $objDomains->search_user_limit);    
                }
                if($objDomains->search_product_limit != null){
                    Config::set('SEARCH_PRODUCT_LIMIT', $objDomains->search_product_limit);    
                }
                if($objDomains->shipment_order_number != null){
                    Config::set('SHIPMENT_ORDER_START_NUMBER', $objDomains->shipment_order_number);    
                }
                if($objDomains->return_shipment_order_number != null){
                    Config::set('RETURN_SHIPMENT_ORDER_START_NUMBER', $objDomains->return_shipment_order_number);    
                }
            }
       }

    }
    public function getGeneralIconLogoTitle($page_id, $section_name)
    {
        $data = [];
        $data['front_icon'] = '/theme/default/images/favicon.ico';
        $data['front_title'] = 'Online Shopping';
        $objThemesettings = ThemeSetting::where(['page' => $page_id, 'sectionname' => $section_name])->first();
        if(!empty($objThemesettings))
        {
            if(!empty($objThemesettings->icon))
            {
                $data['front_icon'] = (file_exists(public_path($objThemesettings->icon[0]['imageurl']))) ? $objThemesettings->icon[0]['imageurl'] : $data['front_icon'];
            }
            $data['front_title'] = ($objThemesettings->title) ?? $data['front_title'];

        }

        return $data;
    }

   

}
