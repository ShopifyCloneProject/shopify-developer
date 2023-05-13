<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'v1', 'as' => 'auth.', 'namespace' => 'FrontAuth'], function () {
	Route::post('userLogin', ['as'=> 'frontlogin', 'uses' => 'LoginController@userLogin']);
	Route::post('register', ['as'=> 'frontregister', 'uses' => 'RegisterController@register']);
	Route::post('password/reset', ['as'=> 'password.reset', 'uses' => 'ForgotPasswordController@sendPasswordResetLink']);
	Route::post('reset/password', ['as'=> 'reset.password', 'uses' => 'ForgotPasswordController@callResetPassword']);
});

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Front', 'middleware' => ['guest']], function () {
	Route::post('address/add', ['as'=> 'address.add', 'uses' => 'CheckoutController@addEditAddress']);
	Route::put('address/edit', ['as'=> 'address.edit', 'uses' => 'CheckoutController@addEditAddress']);
	Route::put('change-defailt-address', ['as'=> 'change.defailt.address', 'uses' => 'CheckoutController@ChangeDafaultAddress']);

	Route::post('cashfree',['as'=> 'cashfree', 'uses' => 'CheckoutController@processCashfree']);
	Route::post('razorpay',['as'=> 'razorpay', 'uses' => 'CheckoutController@processRazorpay']);
	Route::post('instamojo',['as'=> 'instamojo', 'uses' => 'CheckoutController@processInstamojo']);
	Route::post('paytm',['as'=> 'paytm', 'uses' => 'CheckoutController@processPaytm']);
	Route::post('cashondelhivery',['as'=> 'cashondelhivery', 'uses' => 'CheckoutController@processCOD']);
	Route::post('checkout/clearVoucher',['as'=> 'checkout/clearVoucher', 'uses' => 'CheckoutController@clearVoucher']);

	Route::group(['prefix' => 'payment', 'as' => 'payment.'], function () {
	    Route::post('/razorpay', ['as'=> 'razorpay', 'uses' => 'PaymentController@paymentRazorpay']);
	    Route::post('/cashfree', ['as'=> 'cashfree', 'uses' => 'PaymentController@paymentCashFree']);
	    Route::post('/paytm', ['as'=> 'paytm', 'uses' => 'PaymentController@paymentPaytm']);
	    Route::post('/cod', ['as'=> 'cod', 'uses' => 'PaymentController@paymentCOD']);
	});

	Route::get('get-states/{id}',['as'=> 'get-states', 'uses' => 'CheckoutController@getStates']);
	Route::post('/checkTrack/{shipmentId}', ['as' => 'checkTrack', 'uses' => 'OrderController@checkTrack' ]);

});

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Front', 'middleware' => ['auth:sanctum']], function () {
	 
	//collections
	Route::post('updateUser',['as'=> 'updateUser', 'uses' => 'AccountController@updateUser']);
	Route::post('changePassword',['as'=> 'updatePassword', 'uses' => 'AccountController@changePassword']);
	Route::get('authcountLive',['as'=> 'authcountLive', 'uses' => 'HomeController@countLive']);
	Route::get('authcountCart',['as'=> 'authcountcart', 'uses' => 'CartController@countCart']);
	Route::get('authcountCheckout',['as'=> 'authcountCheckout', 'uses' => 'CheckoutController@countCheckout']);
	Route::post('authcheckout/checkVoucher',['as'=> 'checkout.checkVoucher', 'uses' => 'CheckoutController@checkVoucher']);
	Route::post('authcheckout/clearVoucher',['as'=> 'checkout/clearVoucher', 'uses' => 'CheckoutController@clearVoucher']);

	Route::get('authcart', ['as'=> 'authcart', 'uses' => 'CartController@getCartData']);
	Route::post('authcart/add', ['as'=> 'authcart.add', 'uses' => 'CartController@addToCart']);
	Route::delete('authcart/delete/{id}', ['as'=> 'authcart.delete', 'uses' => 'CartController@deleteCart']);
	Route::delete('authcart/clear', ['as'=> 'authcart.clear', 'uses' => 'CartController@clearCart']);
	Route::get('authcart/quantity/remove/{id}', ['as'=> 'authcart.quantity.remove', 'uses' => 'CartController@decreaseQuantity']);
	Route::get('authcart/quantity/add/{id}', ['as'=> 'authcart.quantity.add', 'uses' => 'CartController@increaseQuantity']);

	
	Route::post('orders', ['as' => 'orders', 'uses' => 'OrderController@getAllOrder' ]);

	Route::get('wishlist/add/{pid}', ['as'=> 'wishlist.add', 'uses' => 'WishlistController@addToWishlist']);
	Route::delete('wishlist/delete/{pid}', ['as'=> 'wishlist.delete', 'uses' => 'WishlistController@deleteItemFromWishlist']);
	Route::get('wishlist/{page}', ['as'=> 'wishlist.page', 'uses' => 'WishlistController@getWishlist']);


	Route::get('addresses', ['as'=> 'addresses', 'uses' => 'AccountController@getAddresses']);
	Route::post('addresses/update-address/{id}', ['as'=> 'update-address', 'uses' => 'AccountController@updateAddess']);
	Route::delete('address/delete/{id}', ['as'=> 'address.delete', 'uses' => 'AccountController@removeAddress']);

	Route::get('invoice/download/{id}', ['as'=> 'invoice.download', 'uses' => 'OrderController@downloadInvoice']);

	//exchange order
	Route::delete('cancelExchangeOrder/{orderid}', ['as'=> 'cancelExchangeOrder.delete', 'uses' => 'ExchangeOrderController@cancelExchangeOrder']);
	Route::post('exchangeorder/save-exchange-order',['as'=> 'save-exchange-order', 'uses' => 'ExchangeOrderController@saveexchangeorder']);

	//customer review 
	Route::post('product/detail/add-review',['as'=> 'add-review', 'uses' => 'ProductDetailController@addReview']);

});

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Front' ], function () {
	//collections
	Route::get('cart', ['as'=> 'cart', 'uses' => 'CartController@getCartData']);
	Route::post('cart/add', ['as'=> 'cart.add', 'uses' => 'CartController@addToCart']);
	Route::delete('cart/delete/{cartProductId}', ['as'=> 'cart.delete', 'uses' => 'CartController@deleteCart']);
	Route::delete('cart/clear', ['as'=> 'cart.clear', 'uses' => 'CartController@clearCart']);
	Route::get('cart/quantity/remove/{id}', ['as'=> 'cart.quantity.remove', 'uses' => 'CartController@decreaseQuantity']);
	Route::get('cart/quantity/add/{id}', ['as'=> 'cart.quantity.add', 'uses' => 'CartController@increaseQuantity']);

	Route::get('countLive',['as'=> 'countLive', 'uses' => 'HomeController@countLive']);
	Route::get('countCart',['as'=> 'countcart', 'uses' => 'CartController@countCart']);
	Route::get('countCheckout',['as'=> 'countCheckout', 'uses' => 'CheckoutController@countCheckout']);
	Route::post('checkout/checkVoucher',['as'=> 'checkout.checkVoucher', 'uses' => 'CheckoutController@checkVoucher']);
	Route::post('checkout/clearVoucher',['as'=> 'checkout/clearVoucher', 'uses' => 'CheckoutController@clearVoucher']);

	// Route::post('paytm',['as'=> 'paytm', 'uses' => 'CheckoutController@processPaytm']);
	Route::post('/filter/products', ['as' => 'products.filter', 'uses' => 'CollectionController@getFilterProducts' ]);
	Route::post('/products/all', ['as' => 'products.all', 'uses' => 'ProductController@getProducts' ]);

	//header search
	// Route::get('/search/{title}', ['as' => 'search.title', 'uses' => 'SearchController@search' ]);
	Route::get('/search/{page}/{title}',['as'=> 'search.product', 'uses' => 'SearchController@search']);

	//customer review 
	Route::post('product/detail/add-review',['as'=> 'add-review', 'uses' => 'ProductDetailController@addReview']);
	Route::get('/reviewPage/{page}/{product_id}',['as'=> 'reviewPage', 'uses' => 'ProductDetailController@reviewPage']);
	
});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth:sanctum']], function () {
	//collections
	Route::post('add-collection',['as'=> 'add-collection', 'uses' => 'CollectionsController@store']);
	Route::put('edit-collection',['as'=> 'edit-collection', 'uses' => 'CollectionsController@update']);
	Route::get('get-sort-products/{id}/{stype}',['as'=> 'get-sort-products', 'uses' => 'CollectionsController@getSortProducts']);
	Route::post('get-collection-products',['as'=> 'get-collection-products', 'uses' => 'CollectionsController@getConditionProducts']);
	Route::post('change-order',['as'=> 'change-order', 'uses' => 'CollectionsController@changeSortOrder']);
	Route::get('collection/product/load/{number}/{search?}',['as'=> 'collection.product.load', 'uses' => 'CollectionsController@loadProducts']);
	Route::get('/collection/products/all/',['as'=> 'collection.product.all', 'uses' => 'CollectionsController@getCollectionProducts']);

	Route::post('collection/product/add/',['as'=> 'collection.product.add', 'uses' => 'CollectionsController@addProducts']);
	Route::get('collection/product/all/{id}',['as'=> 'collection.product.all', 'uses' => 'CollectionsController@showAllProducts']);
	Route::get('product/all',['as'=> 'product.all', 'uses' => 'CollectionsController@AllProducts']);

	//customer
	Route::post('add-customer',['as'=> 'add-customer', 'uses' => 'UsersController@store']);
	Route::post('edit-customer',['as'=> 'edit-customer', 'uses' => 'UsersController@updateCustomerDetails']);
	Route::post('add-customer-address',['as'=> 'add-customer-address', 'uses' => 'UsersController@addEditCustomerAddress']);
	Route::put('edit-customer-address',['as'=> 'edit-customer-address', 'uses' => 'UsersController@addEditCustomerAddress']);
	Route::post('/get-sort-orders',['as'=> 'get-sort-orders', 'uses' => 'UsersController@getSortOrders']);

	Route::put('change-tax-status',['as'=> 'change-tax-status', 'uses' => 'UsersController@changeTaxStatus']);
	Route::put('change-subscription-status',['as'=> 'change-subscription-status', 'uses' => 'UsersController@changeSubscriptionStatus']);
	Route::put('add-note',['as'=> 'add-note', 'uses' => 'UsersController@addNote']);
	Route::put('default-address/{id}',['as'=> 'default-address', 'uses' => 'UsersController@defaultAddress']);

	//settings-general settings
	Route::post('save-store-details',['as'=> 'save-store-details', 'uses' => 'UserStoreController@store']);

	//location
	Route::post('add-location', ['as'=> 'add-location', 'uses' => 'AddressesController@addEditLocation']);

	//themes selection
	Route::post('themes/add-theme', ['as'=> 'add-theme', 'uses' => 'ThemeController@store']);
	Route::post('themes/update-theme/{id}', ['as'=> 'update-theme', 'uses' => 'ThemeController@update']);
	Route::post('themes/delete/{id}', ['as'=> 'themes.destroy', 'uses' => 'ThemeController@destroy']);
	Route::post('themes/massdestroy', ['as'=> 'themes.massdestroy', 'uses' => 'ThemeController@massdestroy']);
	Route::post('storeupdateselected-theme',['as' => 'storeupdateselected-theme', 'uses' => 'ThemeController@storeUpdateSelectTheme']);

	//product-type
	Route::post('product-types/delete/{id}', ['as'=> 'product-types.destroy', 'uses' => 'ProductTypesController@destroy']);
	Route::post('product-types/massdestroy', ['as'=> 'product-types.massdestroy', 'uses' => 'ProductTypesController@massdestroy']);

	//product-variant-option
	Route::post('product-variant-options/delete/{id}', ['as'=> 'product-variant-options.destroy', 'uses' => 'ProductVariantOptionsController@destroy']);
	Route::post('product-variant-options/massdestroy', ['as'=> 'product-variant-options.massdestroy', 'uses' => 'ProductVariantOptionsController@massdestroy']);

	//variant-media
	Route::post('variant-media/delete/{id}', ['as'=> 'variant-media.destroy', 'uses' => 'VariantMediaController@destroy']);
	Route::post('variant-media/massdestroy', ['as'=> 'variant-media.massdestroy', 'uses' => 'VariantMediaController@massdestroy']);

	//sales-channels
	Route::post('sales-channels/delete/{id}', ['as'=> 'sales-channels.destroy', 'uses' => 'SalesChannelsController@destroy']);
	Route::post('sales-channels/massdestroy', ['as'=> 'sales-channels.massdestroy', 'uses' => 'SalesChannelsController@massdestroy']);

	//giftcard-denominations
	Route::post('gift-card-denominations/delete/{id}', ['as'=> 'gift-card-denominations.destroy', 'uses' => 'GiftCardDenominationsController@destroy']);
	Route::post('gift-card-denominations/massdestroy', ['as'=> 'gift-card-denominations.massdestroy', 'uses' => 'GiftCardDenominationsController@massdestroy']);

	//giftcardtags
	Route::post('gift-card-tags/delete/{id}', ['as'=> 'gift-card-tags.destroy', 'uses' => 'GiftCardTagsController@destroy']);
	Route::post('gift-card-tags/massdestroy', ['as'=> 'gift-card-tags.massdestroy', 'uses' => 'GiftCardTagsController@massdestroy']);

	//giftcardvendors
	Route::post('gift-card-vendors/delete/{id}', ['as'=> 'gift-card-vendors.destroy', 'uses' => 'GiftCardVendorController@destroy']);
	Route::post('gift-card-vendors/massdestroy', ['as'=> 'gift-card-vendors.massdestroy', 'uses' => 'GiftCardVendorController@massdestroy']);

	//giftcardissue
	Route::post('gift-card-issues/delete/{id}', ['as'=> 'gift-card-issues.destroy', 'uses' => 'GiftCardIssueController@destroy']);
	Route::post('gift-card-issues/massdestroy', ['as'=> 'gift-card-issues.massdestroy', 'uses' => 'GiftCardIssueController@massdestroy']);

	//giftcardcollection
	Route::post('gift-card-collections/delete/{id}', ['as'=> 'gift-card-collections.destroy', 'uses' => 'GiftCardCollectionController@destroy']);
	Route::post('gift-card-collections/massdestroy', ['as'=> 'gift-card-collections.massdestroy', 'uses' => 'GiftCardCollectionController@massdestroy']);

	//orderfinancialstatuses
	Route::post('order-financial-statuses/delete/{id}', ['as'=> 'order-financial-statuses.destroy', 'uses' => 'OrderFinancialStatusController@destroy']);
	Route::post('order-financial-statuses/massdestroy', ['as'=> 'order-financial-statuses.massdestroy', 'uses' => 'OrderFinancialStatusController@massdestroy']);

	//orderproducts
	Route::post('order-products/delete/{id}', ['as'=> 'order-products.destroy', 'uses' => 'OrderFinancialStatusController@destroy']);
	Route::post('order-products/massdestroy', ['as'=> 'order-products.massdestroy', 'uses' => 'OrderFinancialStatusController@massdestroy']);
	
	//orderproductvariants
	Route::post('order-product-variants/delete/{id}', ['as'=> 'order-product-variants.destroy', 'uses' => 'OrderProductVariantsController@destroy']);
	Route::post('order-product-variants/massdestroy', ['as'=> 'order-product-variants.massdestroy', 'uses' => 'OrderProductVariantsController@massdestroy']);

	//stocks
	Route::post('stocks/delete/{id}', ['as'=> 'stocks.destroy', 'uses' => 'StocksController@destroy']);
	Route::post('stocks/massdestroy', ['as'=> 'stocks.massdestroy', 'uses' => 'StocksController@massdestroy']);

	//userstoreindustries
	Route::post('user-store-industries/delete/{id}', ['as'=> 'user-store-industries.destroy', 'uses' => 'UserStoreIndustryController@destroy']);
	Route::post('user-store-industries/massdestroy', ['as'=> 'user-store-industries.massdestroy', 'uses' => 'UserStoreIndustryController@massdestroy']);

		//notifications
	Route::post('notifications/store',['as'=> 'notifications.store', 'uses' => 'NotificationSettingsController@store']);
	Route::post('notifications/update/{id}',['as'=> 'notifications.update', 'uses' => 'NotificationSettingsController@update']);
	Route::post('notifications/delete/{id}',['as'=> 'notifications.destroy', 'uses' => 'NotificationSettingsController@destroy']);
	Route::post('notifications/massdestroy',['as'=> 'notifications.massdestroy', 'uses' => 'NotificationSettingsController@massdestroy']);
    Route::post('settings/notificationdetailssave', ['as' =>'notificationdetailssave', 'uses' => 'NotificationSettingsController@notificationDetailSave']);
   Route::post('settings/notificationreverttodefault', ['as' =>'notificationreverttodefault', 'uses' => 'NotificationSettingsController@revertToDefault']);
	
	//language
	Route::post('storeUpdateSelectLanguage',['as' => 'storeUpdateSelectLanguage', 'uses' => 'LanguageSettingsController@storeUpdateSelectLanguage']);

	//GiftCardSettings
	Route::post('save-giftcard-settings', ['as'=> 'save-giftcard-settings', 'uses' => 'GiftCardSettingsController@saveGiftCardSettings']);
	//Peyment method
	Route::post('activate-payment-method', ['as'=> 'activate-payment-method', 'uses' => 'PaymentSettingsController@activatePaymentMethod']);
	Route::get('deactivate-payment-method/{id}', ['as'=> 'deactivate-payment-method', 'uses' => 'PaymentSettingsController@deActivatePaymentMethod']);
	//
	Route::post('update-checkout-setting', ['as'=> 'update-checkout-setting', 'uses' => 'CheckoutSettingsController@handleSettings']);
	Route::post('update-pages', ['as'=> 'update-pages', 'uses' => 'LegalSettingsController@handlePages']);
	Route::post('save-custom-settings', ['as'=> 'save-custom-settings', 'uses' => 'CustomSettingsController@store']);


	Route::post('settings/home/save', ['as'=> 'settings.home.save', 'uses' => 'PagesController@saveHomePagesSettings']);
	Route::post('settings/menu/save', ['as'=> 'settings.menu.save', 'uses' => 'PagesController@saveMenu']);
	Route::post('settings/page/level', ['as'=> 'settings.page.level', 'uses' => 'PagesController@saveLevelPage']);
	Route::post('settings/productdetail/save', ['as'=> 'settings.productdetail.save', 'uses' => 'PagesController@saveProductDetailSettings']);
	
	Route::post('defaultxml/save', ['as'=> 'defaultxml.save', 'uses' => 'XmlFeedController@saveDefaultXML']);
	Route::post('xmlfeed/create', ['as'=> 'xmlfeed.add', 'uses' => 'XmlFeedController@generateXML']);
	Route::get('xmlfeed/regenerate-xml-file/{id}', ['as'=> 'xmlfeed.regenerate-xml-file', 'uses' => 'XmlFeedController@regenerateXMLFile']);

	Route::post('create-custom-payment-method', ['as'=> 'create-custom-payment-method', 'uses' => 'PaymentSettingsController@createCustomPaymentMethod']);
	Route::get('deactivate-custom-payment-method/{id}', ['as'=> 'deactivate-custom-payment-method', 'uses' => 'PaymentSettingsController@deActivateCustomPaymentMethod']);

	Route::post('add-product',['as'=> 'add-product', 'uses' => 'ProductsController@store']);
	Route::post('update-product',['as'=> 'update-product', 'uses' => 'ProductsController@update']);
	Route::post('search-product',['as'=> 'search-product', 'uses' => 'ProductsController@getSearchProducts']);
	Route::post('products/delete/{id}', ['as'=> 'products.destroy', 'uses' => 'ProductsController@destroy']);
	Route::delete('products/massdestroy', ['as'=> 'products.massdestroy', 'uses' => 'ProductsController@massdestroy']);

	Route::post('add-fbpixel',['as'=> 'add-fbpixel', 'uses' => 'FbpixelController@store']);
	Route::post('update-fbpixel',['as'=> 'update-fbpixel', 'uses' => 'FbpixelController@update']);
	Route::get('get-states/{id}',['as'=> 'get-states', 'uses' => 'UserStoreController@getStates']);

	//order
	Route::post('order/add',['as'=> 'order.add', 'uses' => 'OrdersController@store']);
	Route::post('order/update/{id}',['as'=> 'order.update', 'uses' => 'OrdersController@update']);
	Route::post('order/edit/concact',['as'=> 'order.edit.concact', 'uses' => 'OrdersController@updateContactInformation']);
	Route::post('order/edit/address',['as'=> 'order.edit.address', 'uses' => 'OrdersController@updateShippingAddress']);
	Route::post('order/edit/note',['as'=> 'order.edit.note', 'uses' => 'OrdersController@updateOrderNote']);
	Route::get('order/payment/paid/{id}',['as'=> 'order.paid', 'uses' => 'OrdersController@markAsComplete']);
	Route::get('order/fulfilled/{id}',['as'=> 'order.fulfilled', 'uses' => 'OrdersController@fulfilledOrder']);
	Route::delete('order/delete/{id}', ['as'=> 'order.delete', 'uses' => 'OrdersController@deleteOrder']);
	Route::delete('orderproduct/delete/{id}', ['as'=> 'orderproduct.delete', 'uses' => 'OrdersController@deleteOrderProduct']);

	//shipping detail
	Route::post('saveShippingDetail',['as' => 'saveShippingDetail', 'uses' => 'ShippingDetailController@saveShippingDetail']);

	//shipping product 
	Route::post('order/saveShippingProduct', ['as'=> 'order.saveShippingProduct', 'uses' => 'ShippingController@saveShippingProduct']);
	Route::post('order/save-shipping-order', ['as'=> 'order.save-shipping-order', 'uses' => 'ShippingController@saveShippingOrder']);
	Route::delete('order/deleteShippingOrder/{id}', ['as'=> 'order.deleteShippingOrder', 'uses' => 'ShippingController@deleteShippingOrder']);
	Route::delete('order/deleteShippingOrderProduct/{id}', ['as'=> 'order.deleteShippingOrderProduct', 'uses' => 'ShippingController@deleteShippingOrderProduct']);
	Route::post('handleShippingActions', ['as'=> 'handleShippingActions', 'uses' => 'ShippingController@handleShippingActions']);
	Route::delete('cancelShipping/{shipping_id}', ['as'=> 'cancelShipping', 'uses' => 'ShippingController@cancelShipping']);
	Route::post('availableCouriers', ['as'=> 'availableCouriers', 'uses' => 'ShippingController@availableCouriers']);
	Route::get('pickup/{shipping_id}', ['as'=> 'pickup', 'uses' => 'ShippingController@pickup']);
	Route::get('get-pickup-location/{shipping_id}',['as'=> 'get-pickup-location', 'uses' => 'ShippingController@getPickupLocation']);
	Route::post('trackOrder',['as'=> 'trackOrder', 'uses' => 'ShippingController@trackOrder']);
	Route::post('updateShippingDeliveredStatus',['as'=> 'updateShippingDeliveredStatus', 'uses' => 'ShippingController@updateShippingDeliveredStatus']);
	Route::get('downloadInvoice/{id}', ['as'=> 'downloadInvoice', 'uses' => 'ShippingController@downloadInvoice']);
	Route::get('deleteInvoice/{order_id}', ['as'=> 'deleteInvoice', 'uses' => 'ShippingController@deleteInvoice']);
	Route::post('saveCodPayment/{shipment_id}', ['as'=> 'saveCodPayment', 'uses' => 'ShippingController@saveCodPayment']);

	//return shipping product
	Route::post('save-returnshipping-order', ['as'=> 'save-returnshipping-order', 'uses' => 'ReturnShippingController@saveReturnShippingOrder']);
	Route::post('saveReturnShippingProduct', ['as'=> 'saveReturnShippingProduct', 'uses' => 'ReturnShippingController@saveReturnShippingProduct']);
	Route::delete('deleteReturnShippingOrder/{id}', ['as'=> 'deleteReturnShippingOrder', 'uses' => 'ReturnShippingController@deleteReturnShippingOrder']);
	Route::delete('deleteReturnShippingOrderProduct/{id}', ['as'=> 'deleteReturnShippingOrderProduct', 'uses' => 'ReturnShippingController@deleteReturnShippingOrderProduct']);
	Route::post('editReturnShippingAddress',['as'=> 'editShippingAddress', 'uses' => 'ReturnShippingController@updateShippingAddress']);
	Route::delete('cancelReturnShipping/{return_shipping_id}', ['as'=> 'cancelReturnShipping', 'uses' => 'ReturnShippingController@cancelReturnShipping']);
	Route::post('returnPickup', ['as'=> 'returnPickup', 'uses' => 'ReturnShippingController@returnPickup']);
	Route::post('handleReturnShippingActions', ['as'=> 'handleReturnShippingActions', 'uses' => 'ReturnShippingController@handleReturnShippingActions']);
	Route::post('updateReturnShippingDeliveredStatus',['as'=> 'updateReturnShippingDeliveredStatus', 'uses' => 'ReturnShippingController@updateReturnShippingDeliveredStatus']);
	Route::post('checkTrack/{id}', ['as'=> 'checkTrack', 'uses' => 'ShippingController@checkTrack']);
	

	Route::delete('returnorder/delete/{id}', ['as'=> 'returnorder.delete', 'uses' => 'ReturnOrderController@deleteReturnOrder']);

	Route::get('product/load/{number}/{search?}',['as'=> 'product.load', 'uses' => 'XmlFeedController@loadProducts']);

	Route::get('/xmlfeed/products/all/{id?}',['as'=> 'xmlfeed.product.all', 'uses' => 'XmlFeedController@getCollectionProducts']);	

	Route::post('carts/add-cart', ['as'=> 'add-cart', 'uses' => 'CartController@store']);
	Route::post('carts/update-cart/{id}', ['as'=> 'update-cart', 'uses' => 'CartController@update']);
	Route::post('carts/delete/{id}', ['as'=> 'carts.destroy', 'uses' => 'CartController@destroy']);
	Route::post('carts/massdestroy', ['as'=> 'carts.massdestroy', 'uses' => 'CartController@massdestroy']);

	//country
	Route::get('get-states/{id}',['as'=> 'get-states', 'uses' => 'StatesController@getStates']);

	//front theme settings
	Route::post('themesettings/add-themesetting', ['as'=> 'add-themesetting', 'uses' => 'ThemeSettingsController@addEditFrontTheme']);

	Route::post('refundcashfree',['as'=> 'refundcashfree', 'uses' => 'RefundController@refundRazorpayPay']);
	Route::post('refundrazorpay',['as'=> 'refundrazorpay', 'uses' => 'RefundController@refundPaytm']);
	Route::post('refundinstamojo',['as'=> 'refundinstamojo', 'uses' => 'RefundController@refundCashfree']);
	Route::post('refundpaytm',['as'=> 'refundpaytm', 'uses' => 'RefundController@refundInstamojo']);
	Route::post('orders/addrefundproduct',['as'=> 'addrefundproduct', 'uses' => 'RefundController@refundProduct']);
	Route::post('orders/addexchangeproduct',['as'=> 'addexchangeproduct', 'uses' => 'ExchangeOrderController@addExchangeProduct']);

	Route::delete('returnorderproduct/delete/{id}', ['as'=> 'returnorderproduct.delete', 'uses' => 'ReturnOrderController@deleteReturnOrderProduct']);

	//discount
	Route::post('add-edit-discount',['as'=> 'add-edit-discount', 'uses' => 'DiscountController@addEditDiscount']);

	//Taxes
	Route::post('tax/saveSelectedCountry',['as'=> 'saveSelectedCountry', 'uses' => 'TaxesSettingsController@saveSelectedCountry']);
	Route::post('tax/saveStateTax',['as'=> 'saveStateTax', 'uses' => 'TaxesSettingsController@saveStateTax']);

	//Add Rate
	Route::post('settings/shipping/add-edit-rate',['as'=> 'add-edit-rate', 'uses' => 'ShippingSettingsController@addEditRate']);
	Route::delete('/shipping/delete-rates/{rateId}', ['as' =>'shipping.delete-rates', 'uses' => 'ShippingSettingsController@deleteRates']);
	Route::post('shippingCharge',['as' => 'shippingCharge', 'uses' => 'ShippingSettingsController@shippingCharge']);

	//current transaction
	Route::post('current-status', ['as' => 'payment_current_status', 'uses' => 'CurrenttransactionController@paymentStatus']);
    Route::post('refund-current-status', ['as' => 'refund_current_status', 'uses' => 'CurrentrefundController@refundStatus']);

});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Api\V1','middleware' => ['auth:sanctum']], function () {

		Route::post('cart/serach/product',['as'=> 'cartserachproduct', 'uses' => 'ProductApiController@getSearchProducts']);
		Route::post('cart/serach/user',['as'=> 'cartserachuser', 'uses' => 'UserApiController@getSearchUsers']);
});


Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1','middleware' => ['auth:sanctum']], function () {
		Route::post('returnorder/savereturnorder',['as'=> 'savereturnorder', 'uses' => 'ReturnOrdersController@saveReturnOrder']);
		Route::post('adminapprove',['as'=> 'saveadminapprove', 'uses' => 'ApproveApiController@getAdminApprove']);
		Route::post('orderapprove',['as'=> 'orderapprove', 'uses' => 'ApproveOrderApiController@getOrderAdminApprove']);
		Route::post('shippingapprove',['as'=> 'shippingapprove', 'uses' => 'ApproveShippingApiController@getShippingAdminApprove']);
		Route::post('returnshippingapprove',['as'=> 'returnshippingapprove', 'uses' => 'ApproveReturnShippingApiController@getReturnShippingAdminApprove']);
});
