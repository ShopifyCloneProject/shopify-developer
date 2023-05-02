<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Errors Code
    |--------------------------------------------------------------------------  
    */

    'SUCCESS_STATUS' => 'success',
    'ERROR_STATUS' => 'error',

    'messages' => [
        //State
        'STATE_ADDED_SUCCESSFULLY' => ['code' => '2001', 'msg'=>'State added successfully.'],
        'STATE_DELETE_SUCCESSFULLY' => ['code' => '2002', 'msg'=>'State deleted successfully.'],
        'STATE_UPDATE_SUCCESSFULLY' => ['code' => '2003', 'msg'=>'State updated successfully.'],
        //Role
        'ROLE_ADDED_SUCCESSFULLY' => ['code' => '2004', 'msg'=>'Role added successfully.'],
        'ROLE_DELETE_SUCCESSFULLY' => ['code' => '2005', 'msg'=>'Role deleted successfully.'],
        'ROLE_UPDATE_SUCCESSFULLY' => ['code' => '2006', 'msg'=>'Role updated successfully.'],
        //Permission
        'PERMISSION_ADDED_SUCCESSFULLY' => ['code' => '2007', 'msg'=>'Permission added successfully.'],
        'PERMISSION_DELETE_SUCCESSFULLY' => ['code' => '2008', 'msg'=>'Permission deleted successfully.'],
        'PERMISSION_UPDATE_SUCCESSFULLY' => ['code' => '2009', 'msg'=>'Permission updated successfully.'],
        //Country
        'COUNTRY_ADDED_SUCCESSFULLY' => ['code' => '2010', 'msg'=>'Country added successfully.'],
        'COUNTRY_DELETE_SUCCESSFULLY' => ['code' => '2011', 'msg'=>'Country deleted successfully.'],
        'COUNTRY_UPDATE_SUCCESSFULLY' => ['code' => '2012', 'msg'=>'Country updated successfully.'],
        //WeightManage
        'WEIGHT_ADDED_SUCCESSFULLY' => ['code' => '2013', 'msg'=>'Weight added successfully.'],
        'WEIGHT_DELETE_SUCCESSFULLY' => ['code' => '2014', 'msg'=>'Weight deleted successfully.'],
        'WEIGHT_UPDATE_SUCCESSFULLY' => ['code' => '2015', 'msg'=>'Weight updated successfully.'],
        //PaymenyMethod
        'PAYMENTMETHOD_ADDED_SUCCESSFULLY' => ['code' => '2016', 'msg'=>'PaymenyMethod added successfully.'],
        'PAYMENTMETHOD_DELETE_SUCCESSFULLY' => ['code' => '2017', 'msg'=>'PaymenyMethod deleted successfully.'],
        'PAYMENTMETHOD_UPDATE_SUCCESSFULLY' => ['code' => '2018', 'msg'=>'PaymenyMethod updated successfully.'],
        //Currency
        'CURRENCY_ADDED_SUCCESSFULLY' => ['code' => '2019', 'msg'=>'Currency added successfully.'],
        'CURRENCY_DELETE_SUCCESSFULLY' => ['code' => '2020', 'msg'=>'Currency deleted successfully.'],
        'CURRENCY_UPDATE_SUCCESSFULLY' => ['code' => '2021', 'msg'=>'Currency updated successfully.'],
        //ShippingMethod
        'SHIPPINGMETHOD_ADDED_SUCCESSFULLY' => ['code' => '2022', 'msg'=>'ShippingMethod added successfully.'],
        'SHIPPINGMETHOD_DELETE_SUCCESSFULLY' => ['code' => '2023', 'msg'=>'ShippingMethod deleted successfully.'],
        'SHIPPINGMETHOD_UPDATE_SUCCESSFULLY' => ['code' => '2024', 'msg'=>'ShippingMethod updated successfully.'],
         //TimeZone
        'TIMEZONE_ADDED_SUCCESSFULLY' => ['code' => '2025', 'msg'=>'TimeZone added successfully.'],
        'TIMEZONE_DELETE_SUCCESSFULLY' => ['code' => '2026', 'msg'=>'TimeZone deleted successfully.'],
        'TIMEZONE_UPDATE_SUCCESSFULLY' => ['code' => '2027', 'msg'=>'TimeZone updated successfully.'],
         //Vendor
        'VENDOR_ADDED_SUCCESSFULLY' => ['code' => '2028', 'msg'=>'Vendor added successfully.'],
        'VENDOR_DELETE_SUCCESSFULLY' => ['code' => '2029', 'msg'=>'Vendor deleted successfully.'],
        'VENDOR_UPDATE_SUCCESSFULLY' => ['code' => '2030', 'msg'=>'Vendor updated successfully.'],
         //Variant Type
        'VARIANT_ADDED_SUCCESSFULLY' => ['code' => '2031', 'msg'=>'Variant  added successfully.'],
        'VARIANT_DELETE_SUCCESSFULLY' => ['code' => '2032', 'msg'=>'Variant  deleted successfully.'],
        'VARIANT_UPDATE_SUCCESSFULLY' => ['code' => '2033', 'msg'=>'Variant  updated successfully.'],
         //Variant Options
        'VARIANT_OPTION_ADDED_SUCCESSFULLY' => ['code' => '2034', 'msg'=>'Variant Options added successfully.'],
        'VARIANT_OPTION_DELETE_SUCCESSFULLY' => ['code' => '2035', 'msg'=>'Variant Options deleted successfully.'],
        'VARIANT_OPTION_UPDATE_SUCCESSFULLY' => ['code' => '2036', 'msg'=>'Variant Options updated successfully.'],
         //Tags
        'TAGS_ADDED_SUCCESSFULLY' => ['code' => '2034', 'msg'=>'Tags added successfully.'],
        'TAGS_DELETE_SUCCESSFULLY' => ['code' => '2035', 'msg'=>'Tags deleted successfully.'],
        'TAGS_UPDATE_SUCCESSFULLY' => ['code' => '2036', 'msg'=>'Tags updated successfully.'],
        //collection
        'COLLECTION_ADDED_SUCCESSFULLY' => ['code' => '2037', 'msg'=>'Collection added successfully.'],
        'COLLECTION_DELETE_SUCCESSFULLY' => ['code' => '2038', 'msg'=>'Collection deleted successfully.'],
        'COLLECTION_UPDATE_SUCCESSFULLY' => ['code' => '2039', 'msg'=>'Collection updated successfully.'],
        //Product
        'PRODUCT_ADDED_SUCCESSFULLY' => ['code' => '2040', 'msg'=>'Product added successfully.'],
        'PRODUCT_DELETE_SUCCESSFULLY' => ['code' => '2041', 'msg'=>'Product deleted successfully.'],
        'PRODUCT_UPDATE_SUCCESSFULLY' => ['code' => '2042', 'msg'=>'Product updated successfully.'],

        'SORT_PRODUCT_GET_SUCCESSFULLY' => ['code' => '2043', 'msg'=>'Products order change successfully.'],
        'PRODUCT_GET_SUCCESSFULLY' => ['code' => '2044', 'msg'=>'Product get successfully.'],
        'ORDER_CHANGE_SUCCESSFULLY' => ['code' => '2045', 'msg'=>'Order change successfully.'],

        'STATES_GET_SUCCESSFULLY' => ['code' => '2046', 'msg'=>'States get successfully.'],
        
        //customer
        'CUSTOMER_ADD_SUCCESSFULLY' => ['code' => '2047', 'msg'=>'Customer add successfully.'],
        'CUSTOMER_EDIT_SUCCESSFULLY' => ['code' => '2048', 'msg'=>'Customer details updated successfully.'],
        'CUSTOMER_ADDRESS_ADD_SUCCESSFULLY' => ['code' => '2049', 'msg'=>'Address added successfully.'],
        'CUSTOMER_ADDRESS_EDIT_SUCCESSFULLY' => ['code' => '2050', 'msg'=>'Address updated successfully.'],
        'CUSTOMER_TAX_UPDATE_SUCCESSFULLY' => ['code' => '2051', 'msg'=>'Tax status change successfully.'],
        'CUSTOMER_EMAIL_STATUS_UPDATE_SUCCESSFULLY' => ['code' => '2052', 'msg'=>'Email status change successfully.'],
        'CUSTOMER_STATUS_CHANGE_SUCCESSFULLY' => ['code' => '2053', 'msg'=>'Address status change successfully.'],
        'CUSTOMER_DELETE_SUCCESSFULLY' => ['code' => '2054', 'msg'=>'Customer deleted successfully.'],

         //Settings
        'GENERAL_SETTINGS_SAVED_SUCCESSFULLY' => ['code' => '2055', 'msg'=>'Saved successfully.'],

        'PAYMENT_METHOD_ACTIVATE_SUCCESSFULLY' => ['code' => '2056', 'msg'=> 'Payment method activates successfully.' ],
        'PAYMENT_METHOD_DEACTIVATE_SUCCESSFULLY' => ['code' => '2057', 'msg'=>'Payment method Deactivates successfully.'],

        //PaymenyType
        'PAYMENTTYPE_ADDED_SUCCESSFULLY' => ['code' => '2058', 'msg'=>'PaymenyType added successfully.'],
        'PAYMENTTYPE_DELETE_SUCCESSFULLY' => ['code' => '2059', 'msg'=>'PaymenyType deleted successfully.'],
        'PAYMENTTYPE_UPDATE_SUCCESSFULLY' => ['code' => '2060', 'msg'=>'PaymenyType updated successfully.'],

        //Cart
        'CART_ADDED_SUCCESSFULLY' => ['code' => '2061', 'msg'=>'Product successfully added to cart.'],
        'CART_DELETE_SUCCESSFULLY' => ['code' => '2062', 'msg'=>'Product successfully removed from cart.'],
        'CART_UPDATE_SUCCESSFULLY' => ['code' => '2063', 'msg'=>'Cart updated successfully.'],
        'CART_GET_SUCCESSFULLY' => ['code' => '2064', 'msg'=>'Cart data get successfully.'],

        'MAX_ORDER_NOTIFICATION' => ['code' => '2065', 'msg'=>'Sorry, maximum product quantity exceeded.'],

        'SIGNATURE_GET_SUCCESSFULLY' => ['code' => '2066', 'msg'=>'Payment process start successfully.'],
        'LIVE_CART_COUNT_SUCCESFULLY' => ['code' => '2067', 'msg'=>'Live cart user added successfully.'],
        'LIVE_CHECKOUT_COUNT_SUCCESFULLY' => ['code' => '2068', 'msg'=>'Live Checkout user added successfully.'],
        'LIVE_BUY_COUNT_SUCCESFULLY' => ['code' => '2069', 'msg'=>'Live buy user added successfully.'],

        'ADDRESS_ADDED_SUCCESFULLY' => ['code' => '2070', 'msg'=>'Address added successfully.'],
        'ADDRESS_UPDATED_SUCCESFULLY' => ['code' => '2071', 'msg'=>'Address updated successfully.'],

        //FB Pixel
        'PIXEL_ADDED_SUCCESSFULLY' => ['code' => '2072', 'msg'=>'FBPIXEL added successfully.'],
        'PIXEL_DELETE_SUCCESSFULLY' => ['code' => '2073', 'msg'=>'FBPIXEL deleted successfully.'],
        'PIXEL_UPDATE_SUCCESSFULLY' => ['code' => '2074', 'msg'=>'FBPIXEL updated successfully.'],

        //Order
        'ORDER_UPDATE_SUCCESSFULLY' => ['code' => '2075', 'msg'=>'Order updated successfully.'],
        'CUSTOMER_INFORMATION_UPDATE_SUCCESSFULLY' => ['code' => '2076', 'msg'=>'Data updated successfully.'],
        'MARK_PAID_SUCCESSFULLY' => ['code' => '2077', 'msg'=>'Payment successfully marked as completed.'],
        'ORDER_FULFILL_SUCCESSFULLY' => ['code' => '2078', 'msg'=>'Order fulfill successfully.'],
        'ORDER_DELETED_SUCCESSFULLY' => ['code' => '2079', 'msg'=>'Order deleted successfully.'],
        'ORDER_ARCHIVE_SUCCESSFULLY' => ['code' => '2080', 'msg'=>'Order archive successfully.'],
        'ORDER_UNARCHIVE_SUCCESSFULLY' => ['code' => '2081', 'msg'=>'Order unarchive successfully.'],
        'PRODUCT_ADDED_TO_WISHLIST_SUCCESSFULLY' => ['code' => '2083', 'msg'=>'Product successfully added to wishlist.'],
        'PRODUCT_REMOVE_TO_WISHLIST_SUCCESSFULLY' => ['code' => '2084', 'msg'=>'Product successfully removed to wishlist.'],

        'USER_ORDER_GET_SUCCESSFULLY' => ['code' => '2082', 'msg'=>'User order get successfully.'],
        'PRODUCT_IMPORTED_SUCCESSFULLY' => ['code' => '2085', 'msg'=>'Product imported successfully.'],
        'PLEASE_IMPORT_FILE' => ['code' => '2086', 'msg'=>'Please import csv file first.'],
        'HEADER_MISSING' => ['code' => '2087', 'msg'=>'There was an error importing your CSV file. Please try importing the CSV file again. Invalid CSV Header: Missing headers.'],


        'LIVE_USER_COUNT_SUCCESFULLY' => ['code' => '2088', 'msg'=>'Live user count successfully.'],

        'PRODUCT_MEDIA_UPLOAD_SUCCESFULLY' => ['code' => '2089', 'msg'=>'Product media uploaded successfully.'],
        'CHECKOUT_SETTINGS_SET_SUCCESFULLY' => ['code' => '2090', 'msg'=>'Checkout settings set successfully.'],
        'PAGES_SETTINGS_SET_SUCCESFULLY' => ['code' => '2091', 'msg'=>'Pages set successfully.'],
        'SETTINGS_SET_SUCCESFULLY' => ['code' => '2092', 'msg'=>'Setttings save successfully.'],
        'DATA_GET_SET_SUCCESFULLY' => ['code' => '2093', 'msg'=>'Data get successfully.'],
        'LEVEL_SET_SUCCESFULLY' => ['code' => '2094', 'msg'=>'Menubar set successfully.'],
        'ADDRESS_REMOVE_SUCCESFULLY' => ['code' => '2095', 'msg'=>'Address deleted successfully!'],
        'OUT_OF_STOCK' => ['code' => '2096', 'msg'=>'Product is out of stock!'],
        

        //Section
        'SECTION_ADDED_SUCCESSFULLY' => ['code' => '2097', 'msg'=>'Section added successfully.'],
        'SECTION_DELETE_SUCCESSFULLY' => ['code' => '2098', 'msg'=>'Section deleted successfully.'],
        'SECTION_UPDATE_SUCCESSFULLY' => ['code' => '2099', 'msg'=>'Section updated successfully.'],
        'DEFAULT_XML_SET_SUCCESSFULLY' => ['code' => '3000', 'msg'=>'Default XML Field successfully.'],

        'CUSTOM_SETTINGS_SAVE_SUCCESSFULLY' => ['code' => '3001', 'msg'=> 'Custom settings save successfully.'],

        'XML_REGENERATE_SUCCESSFULLY' => ['code' => '3002', 'msg'=> 'Xml re-generate successfully.'],
        'XML_GENERATE_SUCCESSFULLY' => ['code' => '3003', 'msg'=> 'Xml generate successfully.'],
        'INVOICE_DOWNLOAD_SUCCESSFULLY' => ['code' => '3004', 'msg'=> 'Invoice donwload successfully.'],
        'MENU_SET_SUCCESSFULLY' => ['code' => '3005', 'msg'=> 'Menu set successfully.'],

        'PRODUCT_EXPORT_SUCCESSFULLY' => ['code' => '3006', 'msg'=> 'Product export successfully.'],

        'PRODUCT_IMPORT_MEDIA_PROCESSING' => ['code' => '3007', 'msg'=> 'Import product media process going on.'],
        'PRODUCT_IMPORT_MEDIA_CONVERTING' => ['code' => '3008', 'msg'=> 'Import product media convert process going on.'],

        //Theme
        'THEME_ADDED_SUCCESSFULLY' => ['code' => '3009', 'msg'=>'Theme added successfully.'],
        'THEME_UPDATE_SUCCESSFULLY' => ['code' => '3010', 'msg'=>'Theme updated successfully.'],
        'THEME_SELECTION_SUCCESSFULLY' => ['code' => '3011', 'msg'=>'Theme selection successfully.'],
        'THEME_DELETE_SUCCESSFULLY' => ['code' => '3012', 'msg'=>'Theme delete successfully.'],


        //OTP

        'OTP_SEND_SUCCESSFULLY' => ['code' => '3013', 'msg'=>'OTP Re-sent successfully.'],
        'PLEASE_ENTER_EMAIL' => ['code' => '3014', 'msg'=>'Please enter email'],
        'OTP_MATCHED_SUCCESSFULLY' => ['code' => '3015', 'msg'=>'Otp verified successfully'],
        'OTP_NOT_MATCHED' => ['code' => '3016', 'msg'=>'Otp Not Matched!'],
        'PROFILE_SETTING_UPDATE_SUCCESSFULLY' => ['code' => '3017', 'msg'=>'Profile setting updated Successfully'],

        //Languages
        'LANGUAGE_ADDED_SUCCESSFULLY' => ['code' => '3017', 'msg'=>'Language added successfully.'],
        'LANGUAGE_DELETE_SUCCESSFULLY' => ['code' => '3018', 'msg'=>'Language deleted successfully.'],
        'LANGUAGE_UPDATE_SUCCESSFULLY' => ['code' => '3019', 'msg'=>'Language updated successfully.'],
        'LANGUAGE_SELECTED_SUCCESSFULLY' => ['code' => '3020', 'msg'=>'Language selected successfully.'],

        //Notifications
        'NOTIFICATION_ADDED_SUCCESSFULLY' => ['code' => '3021', 'msg'=>'Notification added successfully.'],
        'NOTIFICATION_DELETE_SUCCESSFULLY' => ['code' => '3022', 'msg'=>'Notification deleted successfully.'],
        'NOTIFICATION_UPDATE_SUCCESSFULLY' => ['code' => '3023', 'msg'=>'Notification updated successfully.'],

        //Product-type
        'PRODUCT_TYPE_DELETE_SUCCESSFULLY' => ['code' => '3024', 'msg'=>'Product_type deleted successfully.'],

        //ProductVariantOptions
        'PRODUCTVARIANT__OPTIONS_DELETE_SUCCESSFULLY' => ['code' => '3025', 'msg'=>'Productvariant_options deleted successfully.'],

        //VARIANTMEDIA
        'VARIANT_MEDIUM_DELETE_SUCCESSFULLY' => ['code' => '3026', 'msg'=>'Variantmedium deleted successfully.'],

        //ProductVariantOptions
        'SALES_CHANNEL_DELETE_SUCCESSFULLY' => ['code' => '3027', 'msg'=>'Saleschannel deleted successfully.'],

        //Giftcard_denominations
        'GIFTCARD_DENOMINATIONS_DELETE_SUCCESSFULLY' => ['code' => '3028', 'msg'=>'Giftcard_denominations deleted successfully.'],

        //Giftcard_vendor
        'GIFTCARD_VENDOR_DELETE_SUCCESSFULLY' => ['code' => '3029', 'msg'=>'giftcard_vendor deleted successfully.'],

        //Giftcard_issue
        'GIFTCARD_ISSUE_DELETE_SUCCESSFULLY' => ['code' => '3030', 'msg'=>'giftcard_issue deleted successfully.'],

        //Giftcard_tags
        'GIFTCARD_TAGS_DELETE_SUCCESSFULLY' => ['code' => '3031', 'msg'=>'giftcard_tags deleted successfully.'],

        //Giftcardcollection
        'GIFTCARD_COLLECTION_DELETE_SUCCESSFULLY' => ['code' => '3032', 'msg'=>'giftcard_collection deleted successfully.'],

        //Orderfinancialstatus
        'ORDER_FINANCIAL_STATUS_DELETE_SUCCESSFULLY' => ['code' => '3033', 'msg'=>'Orderfinancialstatus deleted successfully.'],

        //Orderproducts
        'ORDER_PRODUCTS_DELETE_SUCCESSFULLY' => ['code' => '3034', 'msg'=>'Orderproducts deleted successfully.'],

        //Orderproductvariants
        'ORDER_PRODUCTS_VARIANT_DELETE_SUCCESSFULLY' => ['code' => '3035', 'msg'=>'Orderproduct variants deleted successfully.'],

        //Stocks
        'STOCKS_DELETE_SUCCESSFULLY' => ['code' => '3036', 'msg'=>'Stocks deleted successfully.'],

        //Stocks
        'USERSTORE_INDUSTRY_DELETE_SUCCESSFULLY' => ['code' => '3037', 'msg'=>'Userstore industry deleted successfully.'],

        //user
        'USER_GET_SUCCESSFULLY' => ['code' => '3038', 'msg'=>'User get successfully.'],

        //order
        'ORDER_ADDED_SUCCESSFULLY' => ['code' => '3039', 'msg'=>'Order added successfully.'],

        //Notification user
        'USER_NOTIFICATION_SAVED_SUCCESSFULLY' => ['code' => '3040', 'msg'=>'User notification saved successfully.'],

        //Notification Reverttodefault
        'NOTIFICATION_REVERT_TO_DEFAULT_SUCCESSFULLY' => ['code' => '3041', 'msg'=>'Notification Revert to default successfully.'],

        //Front Theme Setting
        'THEME_SETTINGS_SAVED_SUCCESSFULLY' => ['code' => '3042', 'msg'=>'ThemeSettings saved successfully.'],

        //Refund Payment
        'RAZORPAY_REFUND_SUCCESSFULLY' => ['code' => '3043', 'msg'=>'Razorpay refund successfully.'],
        'RAZORPAY_REFUND_FAILED' => ['code' => '3044', 'msg'=>'Razorpay refund failed.'],
        'PAYTM_REFUND_SUCCESSFULLY' => ['code' => '3045', 'msg'=>'Paytm refund successfully.'],
        'PAYTM_REFUND_FAILED' => ['code' => '3046', 'msg'=>'Paytm refund failed.'],
        'CASHFREE_REFUND_SUCCESSFULLY' => ['code' => '3047', 'msg'=>'Cashfree refund successfully.'],
        'CASHFREE_REFUND_FAILED' => ['code' => '3048', 'msg'=>'Cashfree refund failed.'],
        'INSTAMOJO_REFUND_SUCCESSFULLY' => ['code' => '3049', 'msg'=>'Instamojo refund successfully.'],
        'INSTAMOJO_REFUND_FAILED' => ['code' => '3050', 'msg'=>'Instamojo refund failed.'],
        'ORDER_REFUND_SUCCESSFULLY' => ['code' => '3051', 'msg'=>'Order refund successfully.'],

        //Dimension
        'DIMENSION_ADDED_SUCCESSFULLY' => ['code' => '3052', 'msg'=>'Dimension added successfully.'],
        'DIMENSION_DELETE_SUCCESSFULLY' => ['code' => '3053', 'msg'=>'Dimension deleted successfully.'],
        'DIMENSION_UPDATE_SUCCESSFULLY' => ['code' => '3054', 'msg'=>'Dimension updated successfully.'],

        //Returnorder
        'RETURN_ORDER_SAVED_SUCCESSFULLY' => ['code' => '3055', 'msg'=>'Return order saved successfully.'],
        'RETURN_ORDER_REQUEST_SUCCESSFULLY' => ['code' => '3056', 'msg'=>'Return order request successfully.'],
        'ORDER_APPROVED_SUCCESSFULLY' => ['code' => '3057', 'msg'=>'Order approved successfully.'],

        //Cancelorderrequest
        'CANCEL_REQUEST_SUCCESSFULLY' => ['code' => '3058', 'msg'=>'Cancel request successfully.'],

        //filter customer orders
        'CUSTOMER_ORDER_FILTERED_SUCCESSFULLY' => ['code' => '3059', 'msg'=>'Customer order filtered successfully. '],

        //couriers
        'COURIER_ADDED_SUCCESSFULLY' => ['code' => '3060', 'msg'=>'Courier added successfully.'],
        'COURIER_DELETE_SUCCESSFULLY' => ['code' => '3061', 'msg'=>'Courier deleted successfully.'],
        'COURIER_UPDATE_SUCCESSFULLY' => ['code' => '3062', 'msg'=>'Courier updated successfully.'],

        //tracking
        'TRACKING_ADDED_SUCCESSFULLY' => ['code' => '3063', 'msg'=>'Tracking added successfully.'],
        'TRACKING_DELETE_SUCCESSFULLY' => ['code' => '3064', 'msg'=>'Tracking deleted successfully.'],
        'TRACKING_UPDATE_SUCCESSFULLY' => ['code' => '3065', 'msg'=>'Tracking updated successfully.'],
        
        //shipmentstatus
        'SHIPMENTSTATUS_ADDED_SUCCESSFULLY' => ['code' => '3066', 'msg'=>'Shipmentstatus added successfully.'],
        'SHIPMENTSTATUS_DELETE_SUCCESSFULLY' => ['code' => '3067', 'msg'=>'Shipmentstatus deleted successfully.'],
        'SHIPMENTSTATUS_UPDATE_SUCCESSFULLY' => ['code' => '3068', 'msg'=>'Shipmentstatus updated successfully.'],

        //shiporders
        'SHIPORDERS_ADDED_SUCCESSFULLY' => ['code' => '3069', 'msg'=>'Shiporders added successfully.'],
        'SHIPORDERS_DELETE_SUCCESSFULLY' => ['code' => '3070', 'msg'=>'Shiporders deleted successfully.'],
        'SHIPORDERS_UPDATE_SUCCESSFULLY' => ['code' => '3071', 'msg'=>'Shiporders updated successfully.'],

        //order ship
        'SHIPPING_ORDER_CREATED_SUCCESSFULLY' => ['code' => '3072', 'msg'=>'Shipping order created successfully.'],

        //shipping products
        'PRODUCT_SHIPPED_SUCCESSFULLY' => ['code' => '3073', 'msg'=>'Product shipped successfully.'],
        'SHIPPED_ORDER_CANCELLED_SUCCESSFULLY' => ['code' => '3074', 'msg'=>'Shipped order cancelled successfully.'],
        'SHIPPED_ORDERPRODUCT_CANCELLED_SUCCESSFULLY' => ['code' => '3075', 'msg'=>'Shipped order product cancelled successfully.'],
        'SHIPPING_APPROVE_SUCCESSFULLY' => ['code' => '3076', 'msg'=>'Shipping order approve successfully.'],
        'ACTION_GET_SUCCESSFULLY' => ['code' => '3077', 'msg'=>'Action get successfully.'],

        //shipping detail
        'SHIPPING_DETAIL_SAVED_SUCCESSFULLY' => ['code' => '3078', 'msg'=>'Shipping detail saved successfully.'],

        //return shipping 
        'SHIPPING_PRODUCT_RETURN_SUCCESSFULLY' => ['code' => '3079', 'msg'=>'Shippingproduct return successfully.'],
        'RETURN_PRODUCT_SHIPPED_SUCCESSFULLY' => ['code' => '3080', 'msg'=>'Return product shipped successfully.'],
        'RETURN_SHIPPING_ORDER_DELETED_SUCCESSFULLY' => ['code' => '3081', 'msg'=>'Return shipping order deleted successfully.'],
        'RETURN_SHIPPING_ORDERPRODUCT_CANCELLED_SUCCESSFULLY' => ['code' => '3082', 'msg'=>'Return shipping order product cancelled successfully.'],
        'RETURN_ORDER_DELETED_SUCCESSFULLY' => ['code' => '3083', 'msg'=>'Return order deleted successfully.'],
        'RETURN_SHIPPING_APPROVE_SUCCESSFULLY' => ['code' => '3084', 'msg'=>'Return shipping approve successfully.'],

        //shipping products
        'SHIPPING_ORDER_CANCEL_SUCCESSFULLY' => ['code' => '3085', 'msg'=>'Shipping order cancel successfully.'],

        //return orders
        'RETURN_ORDERS_DELETE_SUCCESSFULLY' => ['code' => '3086', 'msg'=>'Return orders deleted successfully.'],

        //return shipping product
        'RETURN_SHIPPING_PRODUCT_DELETE_SUCCESSFULLY' => ['code' => '3087', 'msg'=>'Return shipping product deleted successfully.'],

        //shipping product
        'SHIPPING_CANCEL_SUCCESSFULLY' => ['code' => '3088', 'msg'=>'Shipping cancelled successfully.'],
        'PICKUP_DONE' => ['code' => '3089', 'msg'=>'Pickup done successfully.'],

        //warehouse
        'PICKUP_LOCATION_GET_SUCCESSFULLY' => ['code' => '3090', 'msg'=>'Pickup location get successfully.'],


        //available couriers
        'COURIER_GET_SUCCESSFULLY' => ['code' => '3091', 'msg'=>'Courier get successfully.'],
        'ORDER_TRACKED_SUCCESSFULLY' => ['code' => '3092', 'msg'=>'Order tracked successfully.'],
        'SHIPPING_TRACKED_SUCCESSFULLY' => ['code' => '3093', 'msg'=>'Shipping tracked successfully.'],

        //returnorderproduct
        'RETURN_ORDER_PRODUCT_DELETE_SUCCESSFULLY' => ['code' => '3094', 'msg'=>'Return order product deleted successfully.'],

        'SHIPPING_SAVED_SUCCESSFULLY' => ['code' => '3095', 'msg'=>'Shipping saved successfully.'],

        //exchangeorder
        'EXCHANGE_ORDER_SAVED_SUCCESSFULLY' => ['code' => '3096', 'msg'=>'Exchange order saved successfully.'],

        //shipping orders
        'SHIPPING_ORDER_DELETE_SUCCESSFULLY' => ['code' => '3100', 'msg'=>'Shipping order deleted successfully.'],
        'SHIPPING_ORDERS_DELETE_SUCCESSFULLY' => ['code' => '3097', 'msg'=>'Shipping orders deleted successfully.'],

        // return shipping orders
        'RETURN_SHIPPING_ORDERS_DELETE_SUCCESSFULLY' => ['code' => '3098', 'msg'=>'Return shipping orders deleted successfully.'],

        //returnexchangeorder
        'RETURN_EXCHANGE_ORDER_SAVED_SUCCESSFULLY' => ['code' => '3099', 'msg'=>'Return exchange order saved successfully.'],

        //cancel exchange order
        'EXCHANGE_ORDER_CANCEL_SUCCESSFULLY' => ['code' => '3101', 'msg'=>'Exchange order cancelled successfully.'],

        //delivered status
        'DELIVERED_STATUS_SAVED_SUCCESSFULLY' => ['code' => '3102', 'msg'=>'Delivered status saved successfully.'],

        //exchange cancel
        'DELIVERED_EXCHANGE_CANCEL_SUCCESSFULLY' => ['code' => '3103', 'msg'=>'Product is delivered you can\'t cancel.'],
        'SHIPPED_EXCHANGE_CANCELED' => ['code' => '3104', 'msg'=>'User exchange poduct cancelled  you can\'t shipping.'],
        'DELIVERED_RETURN_EXCHANGE_CANCEL_SUCCESSFULLY' => ['code' => '3105', 'msg'=>'Return product is delivered you can\'t cancel.'],
        'RETURN_SHIPPED_EXCHANGE_CANCELED' => ['code' => '3106', 'msg'=>'User exchange return poduct cancelled  you can\'t return shipping.'],

        //discount
        'DISCOUNT_SAVED_SUCCESSFULLY' => ['code' => '3107', 'msg'=>'Discount saved successfully.'],
        'DISCOUNT_DELETED_SUCCESSFULLY' => ['code' => '3108', 'msg'=>'Discount deleted successfully.'],
        'DISCOUNT_UPDATED_SUCCESSFULLY' => ['code' => '3109', 'msg'=>'Discount updated successfully.'],

        //tax
        'COUNTRY_TAX_SAVED_SUCCESSFULLY' => ['code' => '3110', 'msg'=>'Country tax saved successfully.'],

        'INVOICE_DELETED_SUCCESSFULLY' => ['code' => '3111', 'msg'=> 'Invoice deleted successfully.'],
        'FILE_NOT_FOUND' => ['code' => '3112', 'msg'=> 'File not found.'],

        'STATE_TAX_SAVED_SUCCESSFULLY' => ['code' => '3113', 'msg'=>'State tax saved successfully.'],

        //rate
        'RATE_SAVED_SUCCESSFULLY' => ['code' => '3114', 'msg'=>'Rate saved successfully.'],
        'RATE_UPDATED_SUCCESSFULLY' => ['code' => '3115', 'msg'=>'Rate updated successfully.'],
        'RATE_DELETED_SUCCESSFULLY' => ['code' => '3116', 'msg'=>'Rate deleted successfully.'],

        //shipping round charge
        'SHIPPING_ROUND_CHARGE_SAVED_SUCCESSFULLY' => ['code' => '3117', 'msg'=>'Shipping round charge saved successfully.'],

        //check voucher 
        'VALID_VOUCHER_CODE' => ['code' => '3118', 'msg'=>'Voucher code applied successfully.'],
        'ORDER_CREATED_FOR_COD' => ['code' => '3119', 'msg'=>'Order created for COD.'],
        'COD_ORDER_SUCCESSFULLY' => ['code' => '3120', 'msg'=>'Cash on delivery Successfully.'],
        

        //clear cartdiscount code
        'DISCOUNT_CODE_CLEARED_SUCCESSFULLY' => ['code' => '3121', 'msg'=>'Discount code cleared Successfully.'],
        'DISCOUNT_ALREADY_EXIST' => ['code' => '3122', 'msg'=>'Discount already used please create other.'],

        //COD payment
        'COD_PAYMENT_SAVED_SUCCESSFULLY' => ['code' => '3123', 'msg'=>'COD payment save Successfully.'],
        
        //review
        'REVIEW_SAVED_SUCCESSFULLY' => ['code' => '3124', 'msg'=>'Review save Successfully.'],


        'PAYMENT_CURRENT_STATUS_SUCCESSFULLY' => ['code' => '3125', 'msg'=>'Payment current status get Successfully.'],

        'REFUND_CURRENT_STATUS_SUCCESSFULLY' => ['code' => '3126', 'msg'=>'Refund current status get Successfully.'],

        //review get
        'REVIEW_GET_SUCCESSFULLY' => ['code' => '3127', 'msg'=>'Review get Successfully.'],

        //import shipping method
        'SHIPPING_IMPORT_SUCCESSFULLY' => ['code' => '3128', 'msg'=>'Shipping method imported Successfully.'],

        //review update
        'REVIEW_UPDATED_SUCCESSFULLY' => ['code' => '3129', 'msg'=>'Review updated Successfully.'],


        'PASSWORD_UPDATE_SUCCESS' => ['code' => '1000', 'msg'=>'Password updated successfully.'],
        'BACKEND_OLD_PASSWORD_WRONG' => ['code' => '1001', 'msg'=>'Old password is wrong.'],
        'DATA_SAVED_SUCCESSFULLY' => ['code' => '1002', 'msg'=>'Data saved successfully.'],
        'DATA_UPDATE_SUCCESSFULLY' => ['code' => '1003', 'msg'=>'Data update successfully.'],

        'USER_REGISTER_SUCCESSFULLY' => ['code' => '1004', 'msg'=>'Your account created successfully. Please sign in to continue. '],
        'USER_EMAIL_ALREADY_REGISTER' => ['code' => '1005', 'msg'=>'Email address is already exist. Please try with different email.'],
        'USER_MOBILE_ALREADY_REGISTER' => ['code' => '1005', 'msg'=>'Mobile number is already exist. Please try different mobile number.'],
        'LOGIN_SUCCESS' => ['code' => '1006', 'msg'=>'Welcome! You are successfully logged in.'],
        'USER_DATA_SUCCESS' => ['code' => '1007', 'msg'=>'User update successfully.'],

        'RESET_DETAIL_NOT_FOUND' => ['code' => '1007', 'msg'=>'Email not found. Please check your email.'],
        'RESET_MAIL_SEND_SUCCESSFULLY' => ['code' => '1008', 'msg'=>'Reset email notification mail send successfully.'],
        'RESET_EMAIL_FAILED' => ['code' => '1009', 'msg'=> "Email could not be sent to this email address."],
        'PASSWORD_RESET_SUCCESSFULLY' => ['code' => '1010', 'msg'=>'Your password has been reset! Please login to continue.'],
        'RESET_PASSWORD_FAILED' => ['code' => '1011', 'msg'=> "Failed to reset password."],
        'RECORD_DELETE_SUCCESSFULLY' => ['code' => '1012', 'msg'=> "Record(s) delete successfully."],
        'RECORD_NOT_FOUND' => ['code' => '1013', 'msg'=> "Record(s) not found."],
    ],    

    'errors' => [ 
     'SOMETHING_WRONG' => ['code' => '7000', 'msg'=>'Something went wrong Please try again.'],
     'BACKEND_OLD_PASSWORD_WRONG' => ['code' => '7001', 'msg'=>'Old password is wrong.'],   
     'PASSWORD_MUST_DIFFERENT' => ['code' => '7002', 'msg'=>'New password must be different from old.'],       
     'ALREADY_EXIST' => ['code' => '7003', 'msg' => 'Record already exist.'],
     'NOT_FOUND' => ['code' => '7004', 'msg' => 'Data not fonund.'],
     'EMAIL_RECORDS_NOT_FOUND' => ['code' => '7005', 'msg' => 'Invalid email/mobile or password. Please try again.'],
     'MOBILE_RECORDS_NOT_FOUND' => ['code' => '7005', 'msg' => 'Invalid email/mobile or password. Please try again.'],
     'LOGIN_INACTIVE' => ['code' => '7005', 'msg'=>'Login is inactive, please contact admin.'],
     'ACCOUNT_BLOCKED' => ['code' => '7005', 'msg'=>'Account is blocked, please contact admin.'],
     'AMOUNT_GRETER_THAN' => ['code' => '7006', 'msg'=>'Amount can not be greate then payment.'],
     'QTY_GRETER_THAN' => ['code' => '7007', 'msg'=>'Please select quantity first.'],
    'INVALID_VOUCHER_CODE' => ['code' => '7008', 'msg'=>'Invalid voucher code.'],
    'INVALID_AMOUNT_VOUCHER_CODE' => ['code' => '7009', 'msg'=>'Please add more items for use this voucher code.'],
    'VOUCHER_CODE_EXPIRED' => ['code' => '7010', 'msg'=>'Voucher code is expired.'],
    'VOUCHER_CODE_LIMIT_EXCEED' => ['code' => '7011', 'msg'=>'Voucher code limit exceed.'],
    'VOUCHER_CODE_NOT_AUTHORIZE' => ['code' => '7012', 'msg'=>'You are not authorize user.'],
    'COD_PAYMENT_FAILED' => ['code' => '7013', 'msg'=>'Cash on delivery failed.'],
    ],


];
