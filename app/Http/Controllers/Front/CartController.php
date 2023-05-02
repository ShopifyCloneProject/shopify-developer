<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Product;
use App\Models\InventoryStock;
use App\Models\LiveUser;
use App\Models\Address;

use Auth;
use Helper;
use Config;
use Exception;

class CartController extends Controller
{
    public function index()
    {   
        $relatedProducts = Product::with('medias')->limit(4)->get();
        $user = $this->checkAuthUser();

        $data = [
            'page' => 'cart',
            'relatedProducts' => $relatedProducts,
            'user'        => $user,
        ];
        if(false){

        }
        else{
            return view('theme.default.pages.cart', compact('data'));
        }
    }

    public function addToCart(Request $request)
    {
        try{
            $params = collect($request->all());
            $storeId = NULL;
            $userId = NULL;
            $macAddr = NULL;
            $cartExist = false;
            if(Auth::check()){
                $user = Auth::user();
                $userId = $user->id;
                $cartsDetails = Cart::where('user_id', $userId)->latest()->first();

                if( $user->store->count() > 0 ){
                    $storeId = $user->store[0]->id;
                }
                if(!empty($cartsDetails))
                {
                    $cartExist = true;
                    $cartId = $cartsDetails->id;
                    $cartDetail = $cartsDetails->cart_detail;
                }
            } else{
                $macAddr = Helper::getMacAddress();
                $cartsDetails = Cart::where('mac_id', $macAddr)->latest()->first();
                if(!empty($cartsDetails))
                {
                    $cartExist = true;
                    $cartId = $cartsDetails->id;
                    $cartDetail = $cartsDetails->cart_detail;
                }
            }
            $productId = $params['productId'];
            $variantOptionId = $params['variantOptionId'];
            $quantity = $params['quantity'];

            $product = Product::select('id','slug', 'min_order_limit', 'max_order_limit', 'is_continue_selling')->where('id', $productId)->first();

            $minOrderLimit = $product->min_order_limit;
            $maxOrderLimit = $product->max_order_limit;

            $found = false; //flag to check product already exist in cart
            $cartDetailId = '';
                
                if(isset($cartDetail)){
                    foreach($cartDetail as $key=>$cart_product){
                        $pId = $cart_product->product_id;
                        if($variantOptionId != ''){ //product is variant product
                            $vOptionId = $cart_product->variant_option_id;
                            if( $productId == $pId && $variantOptionId == $vOptionId )
                            {
                                $found = true;
                                $cartDetailId = $cart_product->id;
                            }
                        } else {//simple product
                            if( $productId == $pId )
                            {
                                $found = true;
                                $cartDetailId = $cart_product->id;
                            }
                        }
                    }
                }

            if($found){
                $cartDetail = CartDetail::where('id', $cartDetailId)->first();
                $qty = $cartDetail->quantity;  
                if(isset($maxOrderLimit) && $maxOrderLimit > 0){
                    if($quantity > $maxOrderLimit){
                        return $this->successResponse(
                            __('constants.SUCCESS_STATUS'),
                            __('constants.messages.MAX_ORDER_NOTIFICATION.code'),
                            __('constants.messages.MAX_ORDER_NOTIFICATION.msg'),
                        );
                    }
                }
                $cartDetail->quantity = $qty + $quantity;
                $cartDetail->save();
            } else {
                if(!$cartExist)
                {
                    $cart = new Cart;  
                    $cart->user_id = $userId;
                    $cart->mac_id = $macAddr;
                    $cart->save();
                    $cartId = $cart->id;
                }

                $cartDetail = new CartDetail;  
                $cartDetail->cart_id = $cartId;
                $cartDetail->product_id = $productId;
                $cartDetail->store_id = $storeId;
                $cartDetail->variant_option_id = $variantOptionId;
                $cartDetail->quantity = $quantity;
                $cartDetail->save();
            }

            $product = $cartDetail->product;
            $variantOption = $cartDetail->variant_options;
            $quantity = $cartDetail->quantity;
            $productImage = '';
            $productImageSrc = [];
            if($product->medias->count() > 0){
                $productImage = $product->medias[0]->src;
                $productImageSrc = $product->medias[0]->image_src;
            }

            $price = 0;
            $productComparePrice = 0;
            $stockStatus = true;
            if(isset($variantOption)){
                $price = $variantOption->price;
                if(isset($variantOption->compare_at_price) && $variantOption->compare_at_price != 0){
                    $productComparePrice = $variantOption->compare_at_price; 
                }
                $variantName = $variantOption->variant_name;
                $productName = $product->title. ' - '. $variantName;
                $stockStatus = $product->stock_status;
            } else {
                $price = $product->price;
                if(isset($product->compare_at_price) && $product->compare_at_price != 0){
                    $productComparePrice = $product->compare_at_price;
                }
                $productName = $product->title;
                $stockStatus = $product->stock_status;
            }

            $cartData = [
                'id' => $cartDetail->id,
                'productId' => $productId,
                'variantOptionId' => $variantOptionId,
                'productName' => $productName,
                'quantity' => $cartDetail->quantity,
                'cartId' => $cartDetail->id,
                'productPrice' => $price,
                'productComparePrice' => $productComparePrice,
                'productImage' => $productImage,
                'productImageSrc' => $productImageSrc,
                'slug' => $product->slug,
                'stock_status' => $stockStatus
            ];

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.CART_ADDED_SUCCESSFULLY.code'),
                __('constants.messages.CART_ADDED_SUCCESSFULLY.msg'),
                $cartData
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

    public function getCartData()
    {
        try{
            $request = request()->input();
            $cartData = [];
            $cartsData = Helper::getCartUserData();
            $subTotal = $taxAmount = $shippingAmount = $voucherAmount = $total = 0;
            $vOptionId = '';
            $productindexkey = 0;
            foreach($cartsData as $intkey=>$cartSingle){
                    foreach($cartSingle->cart_detail as $productkey=>$cart_product){

                    $pId = $cart_product->product_id;
                    $cartDetailId = $cart_product->id;
                    $vOptionId = $cart_product->variant_option_id;
                    $productname = '';
                    $stockStatus = false;
                    $isContinueSelling = true;
                    $productQquantity = 1;

                    if($vOptionId != '' && $vOptionId > 0){ //product is variant product
                        $product = $cart_product->product;
                        $variantOption = $cart_product->variant_options;
                        $quantity = $cart_product->quantity;
                        $variantName = $variantOption->variant_name;
                        $productname = $product->title.' - '.$variantName;
                        $stockStatus = $variantOption->stock_status;
                        $isContinueSelling = $variantOption->is_continue_selling;
                        $productQquantity = $variantOption->quantity;
                        $minOrderLimit = $product->min_order_limit;
                        $maxOrderLimit = $product->max_order_limit;

                        if($variantOption->min_order_limit > 0)
                        {
                            $minOrderLimit = $variantOption->min_order_limit;
                        }

                        if($variantOption->max_order_limit > 0)
                        {
                            $maxOrderLimit = $variantOption->max_order_limit;
                        }
                        //set default image url
                        $productImage = '';
                        $productImageSrc = [];
                        if($variantOption->variant_media->isNotEmpty())
                        {
                             $productImage = $variantOption->variant_media[0]->src;
                        }

                        if($product->medias->count() > 0 && $productImage == ''){
                            $productImage = $product->medias[0]->src;
                            $productImageSrc = $product->medias[0]->image_src;
                        }

                        $productComparePrice = 0;
                        $price = $variantOption->price;
                        if(isset($variantOption->compare_at_price) && $variantOption->compare_at_price != 0){
                            $productComparePrice = $variantOption->compare_at_price;
                        }
                    } else {//simple product
                        $product = $cart_product->product;
                        $variantOption = $cart_product->variant_options;
                        $quantity = $cart_product->quantity;
                        $productname = $product->title;
                        $stockStatus = $product->stock_status;
                        $minOrderLimit = $product->min_order_limit;
                        $maxOrderLimit = $product->max_order_limit;

                        $isContinueSelling = $product->is_continue_selling;
                        $productQquantity = $product->quantity;

                        $productImage = '';
                        $productImageSrc = [];
                        if($product->medias->count() > 0){
                            $productImage = $product->medias[0]->src;
                            $productImageSrc = $product->medias[0]->image_src;
                        }

                        $productComparePrice = 0;
                        $price = $product->price;
                        if(isset($product->compare_at_price) && $product->compare_at_price != 0){
                            $productComparePrice = $product->compare_at_price;
                        }
                    }
                    $subTotal += ($cart_product->quantity * $price);
                    $cartData[$productindexkey++] = [
                        'id' => $cart_product->id,
                        'productId' => $pId,
                        'variantOptionId' => $vOptionId,
                        'productName' => $productname,
                        'quantity' => $cart_product->quantity,
                        'cartId' => $cart_product->cart_id,
                        'productPrice' => $price,
                        'productComparePrice' => $productComparePrice,
                        'productImage' => $productImage,
                        'productImageSrc' => $productImageSrc,
                        'stock_status' => $stockStatus, // true = in stock, false=out ofstock
                        'slug' => $product->slug,
                        'isContinueSelling' => $isContinueSelling,
                        'productQquantity' => $productQquantity,
                        'minOrderLimit' => $minOrderLimit,
                        'maxOrderLimit' => $maxOrderLimit,
                    ];
                    
                }
            if($request['voucher_code'] != "0")
            {
                $voucherAmount = $cartSingle->discount_amount;
            }
            else
            {
                $cartSingle->discount_amount = 0;
                $cartSingle->discount_code = null;
                $cartSingle->save();
            }
            
        }
        $weight = Helper::getCartWeight($cartsData);
        $shippingAmount = Helper::calculateRate($subTotal,$weight);
        if(isset($request['shippingAddressId']) && $request['shippingAddressId'] != 'undefined')
        {
            $objAddress = Address::whereId($request['shippingAddressId'])->first();
            if(!empty($objAddress))
            {
                $taxAmount = Helper::getTaxs($subTotal,$objAddress->state_id, $shippingAmount);
            }

        }
        $subTotal = (string) number_format((float)$subTotal, 2, '.', '');
        $voucherAmount = (string) number_format((float)$voucherAmount, 2, '.', '');
        $total = ($subTotal + $taxAmount + $shippingAmount) - $voucherAmount;
        $total = (string) number_format((float)$total, 2, '.', '');
        
        
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.CART_GET_SUCCESSFULLY.code'),
            __('constants.messages.CART_GET_SUCCESSFULLY.msg'),
            ["products" => $cartData, "subTotal" => $subTotal, "taxAmount" => $taxAmount, "shippingAmount" => $shippingAmount, "voucherAmount" => $voucherAmount, "total" => $total]
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

    public function deleteCart($cartProductId)
    {
        try{
            CartDetail::where('id', $cartProductId)->forcedelete();
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.CART_DELETE_SUCCESSFULLY.code'),
                __('constants.messages.CART_DELETE_SUCCESSFULLY.msg')
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

    public function clearCart()
    {
        try{
            if(Auth::check()){
                $userId = Auth::user()->id;
                $cartIds = Cart::where('user_id', $userId)->pluck('id');
                Cart::where('user_id', $userId)->forcedelete();
                CartDetail::whereIn('cart_id', $cartIds)->forcedelete();
            } else {
                $macAddr = Helper::getMacAddress();
                $cartIds = Cart::where('mac_id', $macAddr)->pluck('id');
                Cart::where('mac_id', $macAddr)->forcedelete();
                CartDetail::whereIn('cart_id', $cartIds)->forcedelete();
            }

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.CART_DELETE_SUCCESSFULLY.code'),
                __('constants.messages.CART_DELETE_SUCCESSFULLY.msg')
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

    public function decreaseQuantity($id)
    {
        try{
            // $userId = Auth::user()->id;
            $cartDetail = CartDetail::where('id', $id)->first();
            if($cartDetail){
                $quantity = $cartDetail->quantity;
                $quantity = $quantity - 1;
                $cartId = $cartDetail->cart_id;
                if($quantity == 0){
                    CartDetail::where('id', $id)->forcedelete();
                } 
                $cartDetail->quantity = $quantity;
                $cartDetail->save();

                $objCart = cartDetail::where('cart_id', $cartId)->count();
                if($objCart == 0){
                    Cart::where('id', $cartId)->forcedelete();
                }


                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.CART_UPDATE_SUCCESSFULLY.code'),
                    __('constants.messages.CART_UPDATE_SUCCESSFULLY.msg')
                );
            } else {
                return $this->errorResponse(
                    __('constants.ERROR_STATUS'),
                    __('constants.errors.NOT_FOUND.code'),
                    __('constants.errors.NOT_FOUND.msg')
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

    public function increaseQuantity($id)
    {
        try{
            // $userId = Auth::user()->id;
            $cartDetail = CartDetail::where('id', $id)->first();
            if($cartDetail){
                $quantity = $cartDetail->quantity;
                $quantity = $quantity + 1;
                $cartDetail->quantity = $quantity;
                $cartDetail->save();
                
                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.CART_UPDATE_SUCCESSFULLY.code'),
                    __('constants.messages.CART_UPDATE_SUCCESSFULLY.msg')
                );
            } else {
                return $this->errorResponse(
                    __('constants.ERROR_STATUS'),
                    __('constants.errors.NOT_FOUND.code'),
                    __('constants.errors.NOT_FOUND.msg')
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

    public  function countCart()
    {
        try {

            $this->setLiveUserCount('cart');
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.LIVE_CART_COUNT_SUCCESFULLY.code'),
                __('constants.messages.LIVE_CART_COUNT_SUCCESFULLY.msg'),
                []
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
}
