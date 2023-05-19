<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/front.php';

Route::redirect('/admin', '/admin/login');

Route::get('/dashboard', function () {
    if (session('status')) {
        return redirect()->route('admin.dashboard')->with('status', session('status'));
    }
    return redirect()->route('admin.dashboard');
});


Route::group(['prefix' => 'admin'], function () {
   Auth::routes(['register' => false]);
});

Route::namespace('Auth')->prefix('admin') ->name('admin.')->group(function () {
    Route::get('password/request', ['as' => 'password.request', 'uses' =>'ForgotPasswordController@showLinkRequestForm']);
    Route::post('password/request/email', ['as' => 'password.request.email', 'uses' =>'ForgotPasswordController@sendResetLinkEmail']);
});
Route::get('adminLogout', ['as' => 'adminLogout', 'uses' => 'Admin\UsersController@logoutAdmin' ]);

Route::namespace('Admin')->prefix('admin') ->name('admin.') ->middleware(['auth']) ->group(function () {


    Route::get('makeXMLFile/{time}', ['as' => 'makeXMLFile', 'uses' =>'HomeController@makeXMLFile']);

    Route::get('makeimage', ['as' => 'makeimage', 'uses' =>'HomeController@makeImage']);
    Route::get('makeimageconvert', ['as' => 'makeimageconvert', 'uses' =>'HomeController@makeImageConvert']);
    Route::get('makeimportimageconvert', ['as' => 'makeimportimageconvert', 'uses' =>'HomeController@makeImportImageConvert']);

    Route::get('change-auth-datatype', 'UsersController@changeDataType');
    Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@dashboardEcommerce' ]);

    // Permissions
    Route::delete('permissions/destroy',['as' => 'permissions.massDestroy', 'uses' => 'PermissionsController@massDestroy']);
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', ['as' => 'roles.massDestroy', 'uses' => 'RolesController@massDestroy']);
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy',['as' => 'users.massDestroy', 'uses' => 'UsersController@massDestroy']);
    Route::post('users/media',['as' => 'users.storeMedia', 'uses' => 'UsersController@storeMedia']);
    Route::post('users/ckmedia',['as' => 'users.storeCKEditorImages', 'uses' => 'UsersController@storeCKEditorImages']);
    Route::resource('users', 'UsersController');

     // Cutomers
    Route::delete('customers/massdestroy',['as' => 'customers.massDestroy', 'uses' => 'UsersController@massDestroy']);
    Route::post('customers/media',['as' => 'customers.storeMedia', 'uses' => 'UsersController@storeMedia']);
    Route::post('customers/ckmedia',['as' => 'customers.storeCKEditorImages', 'uses' => 'UsersController@storeCKEditorImages']);
    Route::resource('customers', 'UsersController');

    // Time Zones
    Route::delete('time-zones/destroy', ['as' => 'time-zones.massDestroy', 'uses' => 'TimeZonesController@massDestroy']);
    Route::resource('time-zones', 'TimeZonesController');

    // Countries
    Route::delete('countries/destroy', ['as' => 'countries.massDestroy', 'uses' => 'CountriesController@massDestroy']);
    Route::resource('countries', 'CountriesController');

    // Weightmanage
    Route::delete('weightmanages/destroy',['as' => 'weightmanages.massDestroy', 'uses' => 'WeightmanageController@massDestroy']);
    Route::resource('weightmanages', 'WeightmanageController');

    // dimensions
    Route::delete('dimensions/destroy',['as' => 'dimensions.massDestroy', 'uses' => 'DimensionController@massDestroy']);
    Route::resource('dimensions', 'DimensionController');

    // States
    Route::delete('states/destroy',['as' => 'states.massDestroy', 'uses' => 'StatesController@massDestroy']);
    Route::resource('states', 'StatesController');

    // Payment Method
    Route::delete('payment-methods/destroy', ['as' => 'payment-methods.massDestroy', 'uses' => 'PaymentMethodController@massDestroy']);
    Route::resource('payment-methods', 'PaymentMethodController');

    // Payment Method
    Route::delete('payment-types/destroy', ['as' => 'payment-types.massDestroy', 'uses' => 'PaymentTypeController@massDestroy']);
    Route::resource('payment-types', 'PaymentTypeController');

    // Currency
    Route::delete('currencies/destroy', ['as' => 'currencies.massDestroy', 'uses' => 'CurrencyController@massDestroy']);
    Route::resource('currencies', 'CurrencyController');

    // Shipping Method
    Route::delete('shipping-methods/destroy',['as' => 'shipping-methods.massDestroy', 'uses' => 'ShippingMethodController@massDestroy']);
    Route::resource('shipping-methods', 'ShippingMethodController');

    // themes
    Route::resource('themes', 'ThemeController');

    //theme selection
    Route::get('/selectedtheme', ['as' =>'selectedtheme', 'uses' => 'ThemeController@selectTheme']);

    //theme settings
       Route::get('/themesettings', ['as' =>'themesettings', 'uses' => 'ThemeSettingsController@index']);
       Route::post('/themesettings/store', ['as' =>'themesettings.addEdit', 'uses' => 'ThemeSettingsController@addEditSetting']);

    // languages
    Route::delete('languages/destroy', ['as' => 'languages.massDestroy', 'uses' => 'LanguageSettingsController@massDestroy']);
    Route::resource('languages', 'LanguageSettingsController');

    // notifications
    Route::resource('notifications', 'NotificationSettingsController');

    // Collections
    Route::delete('collections/destroy',['as' => 'collections.massDestroy', 'uses' => 'CollectionsController@massDestroy']);
    Route::post('collections/media', ['as' => 'collections.storeMedia', 'uses' => 'CollectionsController@storeMedia']);
    Route::post('collections/ckmedia',['as' => 'collections.storeCKEditorImages', 'uses' => 'CollectionsController@storeCKEditorImages']);
    Route::resource('collections', 'CollectionsController');

    // Condition Options
    Route::delete('condition-options/destroy', ['as' => 'condition-options.massDestroy' , 'uses' => 'ConditionOptionsController@massDestroy']);
    Route::resource('condition-options', 'ConditionOptionsController');

    // Collection Conditions
    Route::delete('collection-conditions/destroy', ['as' => 'collection-conditions.massDestroy' , 'uses' => 'CollectionConditionsController@massDestroy']);
    Route::resource('collection-conditions', 'CollectionConditionsController');

    // Variant 
    Route::delete('variant/destroy',['as' => 'variant.massDestroy', 'uses' => 'VariantController@massDestroy']);
    Route::resource('variant', 'VariantController');

    // Condition Titles
    Route::delete('condition-titles/destroy',['as' => 'condition-titles.massDestroy', 'uses' => 'ConditionTitlesController@massDestroy']);
    Route::resource('condition-titles', 'ConditionTitlesController');

    // Vendors
    Route::delete('vendors/destroy',['as' => 'vendors.massDestroy', 'uses' => 'VendorsController@massDestroy']);
    Route::resource('vendors', 'VendorsController');

    // Variant Options
    Route::delete('variant-options/destroy',['as' =>'variant-options.massDestroy', 'uses' => 'VariantOptionsController@massDestroy']);
    Route::resource('variant-options', 'VariantOptionsController');

    // Addresses
    Route::delete('addresses/destroy', ['as' =>'addresses.massDestroy', 'uses' => 'AddressesController@massDestroy']);
    Route::resource('addresses', 'AddressesController');

    // User Store Industry
    Route::delete('user-store-industries/destroy', ['as' =>'user-store-industries.massDestroy', 'uses' => 'UserStoreIndustryController@massDestroy']);
    Route::resource('user-store-industries', 'UserStoreIndustryController');

    // Couriers
    Route::delete('couriers/destroy', ['as' => 'couriers.massDestroy', 'uses' => 'CouriersController@massDestroy']);
    Route::resource('couriers', 'CouriersController');

    // Tracking
    Route::delete('trackings/destroy', ['as' => 'trackings.massDestroy', 'uses' => 'TrackingController@massDestroy']);
    Route::resource('trackings', 'TrackingController');

    // Shipment Status
    Route::delete('shipments/destroy', ['as' => 'shipments.massDestroy', 'uses' => 'ShipmentStatusController@massDestroy']);
    Route::resource('shipments', 'ShipmentStatusController');

    // Ship Orders
    Route::delete('shiporders/destroy', ['as' => 'shiporders.massDestroy', 'uses' => 'ShipOrdersController@massDestroy']);
    Route::resource('shiporders', 'ShipOrdersController');
    

    // Tags
    Route::delete('tags/destroy', ['as' => 'tags.massDestroy', 'uses' => 'TagsController@massDestroy']);
    Route::resource('tags', 'TagsController');

    // Products
    // Route::delete('products/destroy', ['as' => 'products.massDestroy', 'uses' => 'ProductsController@massDestroy']);
    Route::post('products/media', ['as' => 'products.storeMedia', 'uses' => 'ProductsController@storeMedia']);
    Route::post('products/ckmedia', ['as' => 'products.storeCKEditorImages', 'uses' => 'ProductsController@storeCKEditorImages']);
    Route::resource('products', 'ProductsController');
    Route::post('products/file', ['as' => 'products.file', 'uses' => 'ProductsController@storeMedia']);
    Route::post('products/import', ['as' => 'products.import', 'uses' => 'ProductsController@importProducts']);
    Route::post('products/export', ['as' => 'products.export', 'uses' => 'ProductsController@exportProducts']);

    Route::get('products/import/media', ['as' => 'products.import.media', 'uses' => 'ProductsController@startQueue']);

    // Product Types
    Route::delete('product-types/destroy', ['as' => 'product-types.massDestroy', 'uses' => 'ProductTypesController@massDestroy']);
    Route::resource('product-types', 'ProductTypesController');

    // Order Financial Status
    Route::delete('order-financial-statuses/destroy', ['as' => 'order-financial-statuses.massDestroy', 'uses' => 'OrderFinancialStatusController@massDestroy']);
    Route::resource('order-financial-statuses', 'OrderFinancialStatusController');

    // Product Variant Options
    Route::delete('product-variant-options/destroy', ['as' => 'product-variant-options.massDestroy', 'uses' => 'ProductVariantOptionsController@massDestroy']);
    Route::resource('product-variant-options', 'ProductVariantOptionsController');

    // Variant Media
    Route::delete('variant-media/destroy', ['as' => 'variant-media.massDestroy', 'uses' => 'VariantMediaController@massDestroy']);
    Route::resource('variant-media', 'VariantMediaController');

    // Sales Channels
    Route::delete('sales-channels/destroy', ['as' => 'sales-channels.massDestroy', 'uses' => 'SalesChannelsController@massDestroy']);
    Route::resource('sales-channels', 'SalesChannelsController');

    // Gift Card Denominations
    Route::delete('gift-card-denominations/destroy', ['as' => 'gift-card-denominations.massDestroy', 'uses' => 'GiftCardDenominationsController@massDestroy']);
    Route::resource('gift-card-denominations', 'GiftCardDenominationsController');

    // Gift Card Tags
    Route::delete('gift-card-tags/destroy', ['as' => 'gift-card-tags.massDestroy', 'uses' => 'GiftCardTagsController@massDestroy']);
    Route::resource('gift-card-tags', 'GiftCardTagsController');

    // Gift Card Vendor
    Route::delete('gift-card-vendors/destroy', ['as' => 'gift-card-vendors.massDestroy', 'uses' => 'GiftCardVendorController@massDestroy']);
    Route::resource('gift-card-vendors', 'GiftCardVendorController');

    // Gift Card Issue
    Route::delete('gift-card-issues/destroy', ['as' => 'gift-card-issues.massDestroy', 'uses' => 'GiftCardIssueController@massDestroy']);
    Route::resource('gift-card-issues', 'GiftCardIssueController');

     // Discount
    Route::delete('discounts/destroy', ['as' => 'discounts.massDestroy', 'uses' => 'DiscountController@massDestroy']);
    Route::resource('discounts', 'DiscountController');

    // Orders
    Route::delete('orders/destroy', ['as' => 'orders.massDestroy', 'uses' => 'OrdersController@massDestroy']);
    Route::put('/orders/action', ['as' => 'orders.action', 'uses' => 'OrdersController@performAction']);
    Route::resource('orders', 'OrdersController');

   // Refund Orders
    Route::get('/refundorder/{id}', ['as'=> 'orders.refundorder', 'uses' => 'RefundOrderController@refundOrder']);
    Route::get('/refundorderproduct/{id}', ['as'=> 'orders.refundorderproduct', 'uses' => 'RefundOrderController@refundOrderproduct']);

    //check order refund mail 
    // Route::get('mail/orderRefundCheck', ['uses' => 'RefundController@refundSucessTemplate']);

   // Return Orders
    Route::delete('returnorders/destroy', ['as' => 'returnorders.massDestroy', 'uses' => 'ReturnOrderController@massDestroy']);
    Route::resource('returnorders', 'ReturnOrderController');

    // Exchange Orders
    Route::get('returnexchangeorders/{exchangeorderid}', ['as'=> 'returnexchangeorders', 'uses' => 'ExchangeOrderController@exchangeOrders']);
    Route::delete('exchangeorders/destroy', ['as' => 'exchangeorders.massDestroy', 'uses' => 'ExchangeOrderController@massDestroy']);
    Route::resource('exchangeorders', 'ExchangeOrderController');

    // Shipping Orders
    Route::delete('shippingproducts/destroy', ['as' => 'shippingproducts.massDestroy', 'uses' => 'ShippingController@massDestroy']);
    Route::resource('shippingproducts', 'ShippingController');

    //shipping method import
    Route::post('shippingproducts/file', ['as' => 'shippingproducts.file', 'uses' => 'ShippingController@storeMedia']);
    Route::post('shippingproducts/import', ['as' => 'shippingproducts.import', 'uses' => 'ShippingController@importShippingMethod']);

    //shipping confirm mail
    Route::get('shippingConfirmation', ['uses' => 'ShippingController@shippingConfirmation']);
    Route::get('refundRequestSuccess', ['uses' => 'RefundController@refundRequestSuccess']);

    // Return Shipping Orders
    Route::delete('returnshippingproducts/destroy', ['as' => 'returnshippingproducts.massDestroy', 'uses' => 'ReturnShippingController@massDestroy']);
    Route::resource('returnshippingproducts', 'ReturnShippingController');

    // Gift Card Collection
    Route::delete('gift-card-collections/destroy', ['as' => 'gift-card-collections.massDestroy', 'uses' => 'GiftCardCollectionController@massDestroy']);
    Route::resource('gift-card-collections', 'GiftCardCollectionController');

    // Order Product
    Route::delete('order-products/destroy', ['as' => 'order-products.massDestroy', 'uses' => 'OrderProductController@massDestroy']);
    Route::resource('order-products', 'OrderProductController');

    // Cart
    Route::delete('cart/destroy', ['as' => 'cart.massDestroy', 'uses' => 'CartController@massDestroy']);
    Route::resource('cart', 'CartController');

    // Abandone Checkouts
    Route::resource('abandonecheckouts', 'AbandoneCheckoutsController');

    // Inventory Stocks
    Route::delete('inventory-stocks/destroy', ['as' => 'inventory-stocks.massDestroy', 'uses' => 'InventoryStocksController@massDestroy']);
    Route::post('inventory-stocks/media', ['as' => 'inventory-stocks.storeMedia', 'uses' => 'InventoryStocksController@storeMedia']);
    Route::post('inventory-stocks/manage/inventory', ['as' => 'manage.inventory', 'uses' =>'InventoryStocksController@manageInventory']);

    Route::post('inventory-stocks/ckmedia', ['as' => 'inventory-stocks.storeCKEditorImages', 'uses' => 'InventoryStocksController@storeCKEditorImages']);
    Route::resource('inventory-stocks', 'InventoryStocksController');

    // Order Product Variants
    Route::delete('order-product-variants/destroy', ['as'=> 'order-product-variants.massDestroy','uses' => 'OrderProductVariantsController@massDestroy']);
    Route::resource('order-product-variants', 'OrderProductVariantsController');

    // Stocks
    Route::delete('stocks/destroy', ['as'=> 'stocks.massDestroy','uses' => 'StocksController@massDestroy']);
    Route::resource('stocks', 'StocksController');

    // FBPixel
    Route::delete('fbpixels/destroy', ['as' => 'fbpixels.massDestroy', 'uses' => 'FbpixelController@massDestroy']);
    Route::resource('fbpixels', 'FbpixelController');

    // Sections
    Route::delete('sections/destroy', 'SectionsController@massDestroy')->name('sections.massDestroy');
    Route::resource('sections', 'SectionsController');

   
    Route::get('manage-account', ['as' => 'manage-account', 'uses' => 'AccountController@account_settings' ]);
    Route::post('manage-account/resend-otp', ['as' => 'resend-otp', 'uses' => 'AccountController@resendotp' ]);
    Route::post('manage-account/verify-otp', ['as' => 'verify-otp', 'uses' => 'AccountController@verifyotp' ]);
    Route::post('manage-account/update-profile', ['as' => 'update-profile', 'uses' => 'AccountController@updateProfile' ]);
    Route::post('manage-account/account-information', ['as' => 'account-information', 'uses' => 'AccountController@account_information' ]);
    Route::post('manage-account/social-links', ['as' => 'social-links', 'uses' => 'AccountController@social_links' ]);

    Route::get('checkshiprocket', ['as'=> 'shiprocket', 'uses' => 'ShiprocketController@index']);

     Route::group(['prefix' => 'page'], function () {
        Route::get('home', ['as' => 'homepage', 'uses' => 'PagesController@homepage' ]);
        Route::get('menu', ['as' => 'menu', 'uses' => 'PagesController@menu' ]);
        Route::get('home/{level}/{parentid}', ['as' => 'childhomepage', 'uses' => 'PagesController@childhomepage' ]);
        Route::get('productdetail', ['as' => 'productdetail', 'uses' => 'PagesController@indexProductDetail' ]);

       
     });

    Route::group(['prefix' => 'settings','as'=>'settings.'], function () {
       Route::resource('/', 'SettingsController');
       Route::get('/general', ['as' =>'general', 'uses' => 'UserStoreController@index']);
       //location
       Route::get('/locations', ['as' =>'locations', 'uses' => 'AddressesController@index']);
       Route::get('/locations/create', ['as' =>'locations.create', 'uses' => 'AddressesController@create']);
       Route::get('/locations/{id}', ['as' =>'locations.edit', 'uses' => 'AddressesController@edit']);
       
       //Notification settings
       Route::get('/notificationdetails', ['as' =>'notificationdetails', 'uses' => 'NotificationSettingsController@notificationDetails']);
        Route::get('/notificationdetails/template/{id}', ['as' =>'notificationdetailstemplate', 'uses' => 'NotificationSettingsController@notificationDetailTemplate']);

        // User Store
        Route::delete('/user-stores/destroy', ['as' => 'user-stores.massDestroy', 'uses' => 'UserStoreController@massDestroy']);
        Route::resource('/user-stores', 'UserStoreController');

        //send Email check
       Route::get('/sendorderemail/{user_id}', ['as' =>'sendorderemail', 'uses' => 'NotificationSettingsController@sendorderemail']);

       //dynamic file create
       Route::get('/dynamicfilecreate', ['as' =>'dynamicfilecreate', 'uses' => 'NotificationSettingsController@dynamicfilecreate']);

       //giftcards
       Route::get('/gift-cards', ['as' =>'gift-cards', 'uses' => 'GiftCardSettingsController@index']);
       //plan
       Route::get('/plan', ['as' =>'plan', 'uses' => 'PlanSettingsController@index']);
       Route::get('/payments', ['as' =>'payments', 'uses' => 'PaymentSettingsController@index']);
       Route::get('/payments/alternate-providers', ['as' =>'payments.alternate.providers', 'uses' => 'PaymentSettingsController@paymentMeethods']);
       Route::get('/payments/alternate-providers/{id}', ['as' =>'payments.alternate.providers.id', 'uses' => 'PaymentSettingsController@paymentDetails']);

       Route::get('/account', ['as' =>'account', 'uses' => 'AccountSettingsController@index']);
       Route::get('/checkout', ['as' =>'checkout', 'uses' => 'CheckoutSettingsController@index']);
        Route::get('/selectlanguage', ['as' =>'selectlanguage', 'uses' => 'LanguageSettingsController@selectLanguage']);

       Route::get('/shipping', ['as' =>'shipping', 'uses' => 'ShippingSettingsController@index']);
       Route::get('/shipping/local-delivery/{id}', ['as' =>'shipping.local_delivery', 'uses' => 'ShippingSettingsController@localDelivery']);
       Route::get('/shipping/local-pickup/{id}', ['as' =>'shipping.local_pickup', 'uses' => 'ShippingSettingsController@localPickup']);
       Route::get('/shipping/rates/manage/{id}', ['as' =>'shipping.rates.manage', 'uses' => 'ShippingSettingsController@manageRates']);
       Route::get('/shipping/rates/create', ['as' =>'shipping.rates.create', 'uses' => 'ShippingSettingsController@createRates']);
       Route::get('/shipping/add-rates', ['as' =>'shipping.add-rates', 'uses' => 'ShippingSettingsController@addRates']);
       Route::get('/shipping/edit-rates/{rateId}', ['as' =>'shipping.edit-rates', 'uses' => 'ShippingSettingsController@editRates']);

       Route::get('/files', ['as' =>'files', 'uses' => 'FilesSettingsController@index']);
       Route::get('/billing', ['as' =>'billing', 'uses' => 'BillingSettingsController@index']);
       Route::get('/taxes', ['as' =>'taxes', 'uses' => 'TaxesSettingsController@index']);
       Route::get('/statetaxes/{short_code}', ['as' =>'statetaxes', 'uses' => 'TaxesSettingsController@showStateTax']);
       Route::get('/channel', ['as' =>'channel', 'uses' => 'ChannelSettingsController@index']);
       Route::get('/legal', ['as' =>'legal', 'uses' => 'LegalSettingsController@index']);
       Route::get('/fbpixel', ['as' =>'fbpixel', 'uses' => 'FbpixelController@index']);
       
       //xml feed
       Route::resource('xmlfeed', 'XmlFeedController');
       Route::delete('xmlfeed/destroy', ['as' => 'xmlfeed.massDestroy', 'uses' => 'XmlFeedController@destroy']);
       Route::delete('xmlfeed/delete/{id}', ['as' => 'xmlfeed.destroy', 'uses' => 'XmlFeedController@delete']);
       Route::get('/xmlfeed/create', ['as' =>'xmlfeed.create', 'uses' => 'XmlFeedController@create']);
       //custom settings
       Route::get('/custom', ['as' =>'custom', 'uses' => 'CustomSettingsController@index']);
       
       //domain settings
       Route::get('/domainurl', ['as' =>'domainurl', 'uses' => 'DomainUrlController@index']);
       Route::post('/domainurl/store', ['as' =>'domainurl.addEdit', 'uses' => 'DomainUrlController@addEditDomain']);

       //admin settings
       Route::get('/adminsettings', ['as' =>'adminsettings', 'uses' => 'AdminSettingsController@index']);
       Route::post('/adminsettings/store', ['as' =>'adminsettings.addEdit', 'uses' => 'AdminSettingsController@addEditSetting']);

       //shipment details
       Route::get('/shippingdetails', ['as' =>'shippingdetails', 'uses' => 'ShippingDetailController@index']);

       // Default Sections
       Route::get('/default-xml-section', ['as' => 'default-xml-section', 'uses'=>'XmlFeedController@getDefaultXMLSettings']);

    });

    /* Route Apps */
    Route::group(['prefix' => 'app'], function () {
      Route::get('email', ['as' =>'app-email', 'uses' => 'AppsController@emailApp']);
      Route::get('chat', ['as' => 'app-chat', 'uses' => 'AppsController@chatApp']);
      Route::get('todo', ['as' => 'app-todo', 'uses' => 'AppsController@todoApp']);
      Route::get('calendar', ['as' =>'app-calendar', 'uses' => 'AppsController@calendarApp']);
      Route::get('kanban', ['as' => 'app-kanban', 'uses' => 'AppsController@kanbanApp']);
      Route::get('invoice/list', ['as' => 'app-invoice-list', 'uses' => 'AppsController@invoice_list' ]);
      Route::get('invoice/preview', ['as' => 'app-invoice-preview', 'uses' => 'AppsController@invoice_preview' ]);
      Route::get('invoice/edit', ['as' => 'app-invoice-edit', 'uses' => 'AppsController@invoice_edit' ]);
      Route::get('invoice/add', ['as' => 'app-invoice-add', 'uses' => 'AppsController@invoice_add' ]);
      Route::get('invoice/print', ['as' => 'app-invoice-print', 'uses' => 'AppsController@invoice_print' ]);
      Route::get('ecommerce/shop', ['as' => 'app-ecommerce-shop', 'uses' => 'AppsController@ecommerce_shop' ]);
      Route::get('ecommerce/details', ['as' => 'app-ecommerce-details', 'uses' => 'AppsController@ecommerce_details' ]);
      Route::get('ecommerce/wishlist', ['as' => 'app-ecommerce-wishlist', 'uses' => 'AppsController@ecommerce_wishlist' ]);
      Route::get('ecommerce/checkout', ['as' => 'app-ecommerce-checkout', 'uses' => 'AppsController@ecommerce_checkout' ]);
      Route::get('file-manager', ['as' => 'app-file-manager', 'uses' => 'AppsController@file_manager' ]);
      Route::get('user/list', ['as' => 'app-user-list', 'uses' => 'AppsController@user_list' ]);
      Route::get('user/view', ['as' => 'app-user-view', 'uses' => 'AppsController@user_view' ]);
      Route::get('user/edit', ['as' => 'app-user-edit', 'uses' => 'AppsController@user_edit' ]);
    });
    /* Route Apps */

    /* Route UI */
    Route::group(['prefix' => 'ui'], function () {
      Route::get('typography', ['as' => 'ui-typography', 'uses' => 'UserInterfaceController@typography' ]);
      Route::get('colors', ['as' => 'ui-colors', 'uses' => 'UserInterfaceController@colors' ]);
    });
    /* Route UI */

    /* Route Icons */
    Route::group(['prefix' => 'icons'], function () {
      Route::get('feather', ['as' => 'icons-feather', 'uses' => 'UserInterfaceController@icons_feather' ]);
    });
    /* Route Icons */

    /* Route Cards */
    Route::group(['prefix' => 'card'], function () {
      Route::get('basic', ['as' => 'card-basic', 'uses' => 'CardsController@card_basic' ]);
      Route::get('advance', ['as' => 'card-advance', 'uses' => 'CardsController@card_advance' ]);
      Route::get('statistics', ['as' => 'card-statistics', 'uses' => 'CardsController@card_statistics' ]);
      Route::get('analytics', ['as' => 'card-analytics', 'uses' => 'CardsController@card_analytics' ]);
      Route::get('actions', ['as' => 'card-actions', 'uses' => 'CardsController@card_actions' ]);
    });
    /* Route Cards */

    /* Route Components */
    Route::group(['prefix' => 'component'], function () {
      Route::get('alert', ['as' => 'component-alert', 'uses' => 'ComponentsController@alert' ]);
      Route::get('avatar', ['as' => 'component-avatar', 'uses' => 'ComponentsController@avatar' ]);
      Route::get('badges', ['as' => 'component-badges', 'uses' => 'ComponentsController@badges' ]);
      Route::get('breadcrumbs', ['as' => 'component-breadcrumbs', 'uses' => 'ComponentsController@breadcrumbs' ]);
      Route::get('buttons', ['as' => 'component-buttons', 'uses' => 'ComponentsController@buttons' ]);
      Route::get('carousel', ['as' => 'component-carousel', 'uses' => 'ComponentsController@carousel' ]);
      Route::get('collapse', ['as' => 'component-collapse', 'uses' => 'ComponentsController@collapse' ]);
      Route::get('divider', ['as' => 'component-divider', 'uses' => 'ComponentsController@divider' ]);
      Route::get('dropdowns', ['as' => 'component-dropdowns', 'uses' => 'ComponentsController@dropdowns' ]);
      Route::get('list-group', ['as' => 'component-list-group', 'uses' => 'ComponentsController@list_group' ]);
      Route::get('modals', ['as' => 'component-modals', 'uses' => 'ComponentsController@modals' ]);
      Route::get('pagination', ['as' => 'component-pagination', 'uses' => 'ComponentsController@pagination' ]);
      Route::get('navs', ['as' => 'component-navs', 'uses' => 'ComponentsController@navs' ]);
      Route::get('tabs', ['as' => 'component-tabs', 'uses' => 'ComponentsController@tabs' ]);
      Route::get('timeline', ['as' => 'component-timeline', 'uses' => 'ComponentsController@timeline' ]);
      Route::get('pills', ['as' => 'component-pills', 'uses' => 'ComponentsController@pills' ]);
      Route::get('tooltips', ['as' => 'component-tooltips', 'uses' => 'ComponentsController@tooltips' ]);
      Route::get('popovers', ['as' => 'component-popovers', 'uses' => 'ComponentsController@popovers' ]);
      Route::get('pill-badges', ['as' => 'component-pill-badges', 'uses' => 'ComponentsController@pill_badges' ]);
      Route::get('progress', ['as' => 'component-progress', 'uses' => 'ComponentsController@progress' ]);
      Route::get('media-objects', ['as' => 'component-media-objects', 'uses' => 'ComponentsController@media_objects' ]);
      Route::get('spinner', ['as' => 'component-spinner', 'uses' => 'ComponentsController@spinner' ]);
      Route::get('toast', ['as' => 'component-toast', 'uses' => 'ComponentsController@toast' ]);
    });
    /* Route Components */

    /* Route Extensions */
    Route::group(['prefix' => 'ext-component'], function () {
      Route::get('sweet-alerts', ['as' => 'ext-component-sweet-alerts', 'uses' => 'ExtensionController@sweet_alert' ]);
      Route::get('block-ui', ['as' => 'ext-component-block-ui', 'uses' => 'ExtensionController@block_ui' ]);
      Route::get('toastr', ['as' => 'ext-component-toastr', 'uses' => 'ExtensionController@toastr' ]);
      Route::get('slider', ['as' => 'ext-component-slider', 'uses' => 'ExtensionController@slider' ]);
      Route::get('drag-drop', ['as' => 'ext-component-drag-drop', 'uses' => 'ExtensionController@drag_drop' ]);
      Route::get('tour', ['as' => 'ext-component-tour', 'uses' => 'ExtensionController@tour' ]);
      Route::get('clipboard', ['as' => 'ext-component-clipboard', 'uses' => 'ExtensionController@clipboard' ]);
      Route::get('plyr', ['as' => 'ext-component-plyr', 'uses' => 'ExtensionController@plyr' ]);
      Route::get('context-menu', ['as' => 'ext-component-context-menu', 'uses' => 'ExtensionController@context_menu' ]);
      Route::get('swiper', ['as' => 'ext-component-swiper', 'uses' => 'ExtensionController@swiper' ]);
     Route::get('tree', ['as' => 'ext-component-tree', 'uses' => 'ExtensionController@tree' ]);
     Route::get('ratings', ['as' => 'ext-component-ratings', 'uses' => 'ExtensionController@ratings' ]);
      Route::get('locale', ['as' => 'ext-component-locale', 'uses' => 'ExtensionController@locale' ]);
    });
    /* Route Extensions */

    /* Route Page Layouts */
    Route::group(['prefix' => 'page-layouts'], function () {
      Route::get('collapsed-menu', ['as' => 'layout-collapsed-menu', 'uses' => 'PageLayoutController@layout_collapsed_menu' ]);
      Route::get('boxed', ['as' => 'layout-boxed', 'uses' => 'PageLayoutController@layout_boxed' ]);
      Route::get('without-menu', ['as' => 'layout-without-menu', 'uses' => 'PageLayoutController@layout_without_menu' ]);
      Route::get('empty', ['as' => 'layout-empty', 'uses' => 'PageLayoutController@layout_empty' ]);
      Route::get('blank', ['as' => 'layout-blank', 'uses' => 'PageLayoutController@layout_blank' ]);
    });
    /* Route Page Layouts */

    /* Route Forms */
    Route::group(['prefix' => 'form'], function () {
      Route::get('input', ['as' => 'form-input', 'uses' => 'FormsController@input' ]);
      Route::get('input-groups', ['as' => 'form-input-groups', 'uses' => 'FormsController@input_groups' ]);
      Route::get('input-mask', ['as' => 'form-input-mask', 'uses' => 'FormsController@input_mask' ]);
      Route::get('textarea', ['as' => 'form-textarea', 'uses' => 'FormsController@textarea' ]);
      Route::get('checkbox', ['as' => 'form-checkbox', 'uses' => 'FormsController@checkbox' ]);
      Route::get('radio', ['as' => 'form-radio', 'uses' => 'FormsController@radio' ]);
      Route::get('switch', ['as' => 'form-switch', 'uses' => 'FormsController@switch' ]);
      Route::get('select', ['as' => 'form-select', 'uses' => 'FormsController@select' ]);
      Route::get('number-input', ['as' => 'form-number-input', 'uses' => 'FormsController@number_input' ]);
      Route::get('file-uploader', ['as' => 'form-file-uploader', 'uses' => 'FormsController@file_uploader' ]);
     // Route::get('quill-editor', ['as' => 'form-quill-editor', 'uses' => 'FormsController@quill_editor' ]);
      Route::get('date-time-picker', ['as' => 'form-date-time-picker', 'uses' => 'FormsController@date_time_picker' ]);
      Route::get('layout', ['as' => 'form-layout', 'uses' => 'FormsController@layouts' ]);
      Route::get('wizard', ['as' => 'form-wizard', 'uses' => 'FormsController@wizard' ]);
      Route::get('validation', ['as' => 'form-validation', 'uses' => 'FormsController@validation' ]);
      Route::get('repeater', ['as' => 'form-repeater', 'uses' => 'FormsController@form_repeater' ]);
    });
    /* Route Forms */

    /* Route Tables */
    Route::group(['prefix' => 'table'], function () {
      Route::get('', ['as' => 'table-page-profile', 'uses' => 'TableController@table' ]);
      Route::get('datatable/basic', ['as' => 'table-page-profile-basic', 'uses' => 'TableController@datatable_basic' ]);
      Route::get('datatable/advance', ['as' => 'table-page-profile-advance', 'uses' => 'TableController@datatable_advance' ]);
      Route::get('ag-grid', ['as' => 'able-page-profile-grid', 'uses' => 'TableController@ag_grid' ]);
    });
    /* Route Tables */

    /* Route Pages */
    Route::group(['prefix' => 'page'], function () {
      Route::get('account-settings', ['as' => 'page-account-settings', 'uses' => 'PagesController@account_settings' ]);
      Route::get('profile', ['as' => 'page-profile', 'uses' => 'PagesController@profile' ]);
      Route::get('faq', ['as' => 'page-faq', 'uses' => 'PagesController@faq' ]);
      Route::get('knowledge-base', ['as' => 'page-knowledge-base-basic', 'uses' => 'PagesController@knowledge_base' ]);
      Route::get('knowledge-base/category', ['as' => 'page-knowledge-base-category', 'uses' => 'PagesController@kb_category' ]);
      Route::get('knowledge-base/category/question', ['as' => 'page-knowledge-base-categoryquestion', 'uses' => 'PagesController@kb_question' ]);
      Route::get('pricing', ['as' => 'page-pricing', 'uses' => 'PagesController@pricing' ]);
      Route::get('blog/list', ['as' => 'page-blog-list', 'uses' => 'PagesController@blog_list' ]);
      Route::get('blog/detail', ['as' => 'page-blog-detail', 'uses' => 'PagesController@blog_detail' ]);
      Route::get('blog/edit', ['as' => 'page-blog-edit', 'uses' => 'PagesController@blog_edit' ]);

      // Miscellaneous Pages With Page Prefix
      Route::get('coming-soon', ['as' => 'misc-coming-soon', 'uses' => 'MiscellaneousController@coming_soon' ]);
      Route::get('not-authorized', ['as' => 'misc-not-authorized', 'uses' => 'MiscellaneousController@not_authorized' ]);
      Route::get('maintenance', ['as' => 'misc-maintenance', 'uses' => 'MiscellaneousController@maintenance' ]);

    });

    /* Route Pages */
    // Route::get('/error', ['as' => 'error', 'uses' => 'MiscellaneousController@error' ]);

    /* Route Authentication Pages */
    Route::group(['prefix' => 'auth'], function () {
      Route::get('login-v1', ['as' => 'auth-login-v1', 'uses' => 'AuthenticationController@login_v1' ]);
      Route::get('login-v2', ['as' => 'auth-login-v2', 'uses' => 'AuthenticationController@login_v2' ]);
      Route::get('register-v1', ['as' => 'auth-register-v1', 'uses' => 'AuthenticationController@register_v1' ]);
      Route::get('register-v2', ['as' => 'auth-register-v2', 'uses' => 'AuthenticationController@register_v2' ]);
      Route::get('forgot-password-v1', ['as' => 'auth-forgot-password-v1', 'uses' => 'AuthenticationController@forgot_password_v1' ]);
      Route::get('forgot-password-v2', ['as' => 'auth-forgot-password-v2', 'uses' => 'AuthenticationController@forgot_password_v2' ]);
      Route::get('reset-password-v1', ['as' => 'reset-password-v1', 'uses' => 'AuthenticationController@reset_password_v1' ]);
      Route::get('reset-password-v2', ['as' => 'reset-password-v2', 'uses' => 'AuthenticationController@reset_password_v2' ]);
      Route::get('lock-screen', ['as' => 'lock-screen', 'uses' => 'AuthenticationController@lock_screen' ]);
    });

    Route::get('current-status', ['as' => 'payment_current_status', 'uses' => 'CurrenttransactionController@paymentStatus']);
    Route::get('refund-current-status', ['as' => 'refund_current_status', 'uses' => 'CurrentrefundController@refundStatus']);
    /* Route Authentication Pages */

    /* Route Charts */
   /* Route::group(['prefix' => 'chart'], function () {
      Route::get('apex', ['as' => 'chart-apex', 'uses' => 'ChartsController@apex' ]);
      Route::get('chartjs', ['as' => 'chart-chartjs', 'uses' => 'ChartsController@chartjs' ]);
      Route::get('echarts', ['as' => 'chart-echarts', 'uses' => 'ChartsController@echarts' ]);
    });*/
    /* Route Charts */

    // map leaflet
    // Route::get('/maps/leaflet', ['as' => 'map-leaflet', 'uses' => 'ChartsController@maps_leaflet' ]);

    // locale Route
   // Route::get('lang/{locale}', [ 'uses' => 'LanguageController@swap' ]);

});


Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    Route::post('change-password', 'ChangePasswordController@ChangePassword')->name('password.updateProfile');
    Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
    Route::post('password', 'ChangePasswordController@update')->name('password.update');
    Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
});


Route::get('setLanguage', function () {
     Artisan::call('cache:clear');
    $strings = Cache::rememberForever('lang.js', function () {
        $lang = config('app.locale');

        $files   = glob(resource_path('lang/' . $lang . '/*.php'));
        $strings = [];

        foreach ($files as $file) {
            $name           = basename($file, '.php');
            $strings[$name] = require $file;
        }

        return $strings;
    });

   $myfile = fopen(public_path("js/lang.js"), "w") or die("Unable to open file!");
   $txt = 'window.i18n = ' . json_encode($strings);
   fwrite($myfile, $txt);
   fclose($myfile);



   $frontstrings = Cache::rememberForever('frontlang.js', function () {
        $lang = config('app.locale');

        $frontfiles   = glob(resource_path('lang/'. $lang .'/front/*.php'));
        $frontstrings = [];

        foreach ($frontfiles as $frontfile) {
            $name           = basename($frontfile, '.php');
            $frontstrings[$name] = require $frontfile;
        }

        return $frontstrings;
    });
    echo public_path("js/frontlang.js");
   $frontmyfile = fopen(public_path("js/frontlang.js"), "w") or die("Unable to open file!");
   $fronttxt = 'window.i18n = ' . json_encode($frontstrings);
   fwrite($frontmyfile, $fronttxt);
   fclose($frontmyfile);
   dd('Write Language Successfully');
    exit();
})->name('assets.lang');
