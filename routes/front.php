<?php

Route::namespace('FrontAuth')->name('auth.')->group(function () {
    Route::get('/login', ['as' => 'home', 'uses' => 'LoginController@index']);
    Route::get('/logout', ['as' => 'logout', 'uses' => 'LoginController@logout']);
    Route::get('/password/reset', ['as' => 'password.reset', 'uses' => 'ForgotPasswordController@index']);

    Route::get('/password/reset/{token}', ['as' => 'reset.password.get', 'uses' => 'ForgotPasswordController@showResetPasswordForm']);
});

Route::namespace('Front')->name('client.')->group(function () {
 
});

Route::group(['namespace' => 'Front', 'middleware' => ['guest']], function () {
    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index' ]);
    Route::get('/notFound', ['as' => 'pagenotfound', 'uses' => 'HomeController@pagenotfound' ]);
    Route::get('/refund', ['as' => 'refund', 'uses' => 'HomeController@refund' ]);
    Route::get('/privacy', ['as' => 'privacy', 'uses' => 'HomeController@privacy' ]);
    Route::get('/termsandconditions', ['as' => 'termsandconditions', 'uses' => 'HomeController@termsandconditions' ]);
    Route::get('/shippingpolicy', ['as' => 'shippingpolicy', 'uses' => 'HomeController@shippingpolicy' ]);

    Route::get('/checkout', ['as' => 'checkout', 'uses' => 'CheckoutController@index' ]);
    Route::get('/thank-you', ['as' => 'thankyou', 'uses' => 'AccountController@thankyou' ]);
    Route::group(['prefix' => 'payment'], function () {
        Route::get('/instamojo', ['as'=> 'instamojo', 'uses' => 'PaymentController@paymentInstamojo']);
        Route::post('/razorpay', ['as'=> 'razorpay', 'uses' => 'PaymentController@paymentRazorpay']);
    });

    //shop
    Route::get('/products/all', ['as' => 'collections.all', 'uses' => 'ProductController@index' ]);
    Route::get('invoice/download/{id}', ['as'=> 'invoice.download', 'uses' => 'OrderController@downloadInvoice1']);
    
    //collection
    Route::get('/collections', ['as' => 'collections', 'uses' => 'CollectionController@index' ]);
    Route::get('/collections/{slug}', ['as' => 'collections.detail', 'uses' => 'CollectionController@detail' ]);

    Route::get('/product/detail/{id}', ['as' => 'product-detail', 'uses' => 'ProductDetailController@index' ]);
    Route::get('/cart', ['as' => 'cart', 'uses' => 'CartController@index' ]);
    Route::get('/search', ['as' => 'search', 'uses' => 'SearchController@index' ]);
});

Route::group(['namespace' => 'Front', 'middleware' => ['auth:sanctum']], function () {
    Route::get('account', ['as' => 'account', 'uses' => 'AccountController@index' ]);
    Route::get('changepassword', ['as' => 'accountchangepassword', 'uses' => 'AccountController@changepasswordpage' ]);
    Route::get('addresses', ['as' => 'addresses', 'uses' => 'AccountController@addresses' ]);
    Route::get('orders', ['as' => 'orders', 'uses' => 'OrderController@orders' ]);
    Route::get('order/{order_id}/{order_product_id}', ['as' => 'orderdata', 'uses' => 'OrderController@orderindex' ]);
    Route::get('returnorder/{order_product_id}', ['as' => 'returnorders', 'uses' => 'OrderController@returnOrder' ]);
    Route::get('exchangeorder/{exchangeOrderId}/{exchangeOrderproductid}', ['as' => 'exchangeorder', 'uses' => 'OrderController@exchangeOrder' ]);
    Route::get('cancelexchangeorder/{latestExchangeOrderId}/{latestExchangeOrderProductId}', ['as' => 'cancelexchangeorder', 'uses' => 'OrderController@cancelExchangeOrder' ]);
    Route::get('cancelrequest', ['as' => 'cancelrequest', 'uses' => 'OrderController@cancelRequest' ]);
    //wishlist
    Route::get('/wishlist', ['as' => 'wishlist', 'uses' => 'WishlistController@index' ]);


});

 
    Route::get('mail/check', ['uses' => 'TempMailController@orderMailCheck']);
    Route::get('weight/check', ['uses' => 'TempMailController@checkWeight']);
    Route::get('abandonedCheckout', ['uses' => 'front\CheckoutController@abandonedCheckout']);
    Route::get('paymentError', ['uses' => 'front\CheckoutController@paymentError']);
    Route::get('pendingPaymentError', ['uses' => 'front\CheckoutController@pendingPaymentError']);
    Route::get('pendingPaymentSuccess', ['uses' => 'front\CheckoutController@pendingPaymentSuccess']);

?>