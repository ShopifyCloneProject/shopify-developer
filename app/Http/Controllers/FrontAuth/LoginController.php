<?php

namespace App\Http\Controllers\FrontAuth;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartDetail;

use Auth;
use Redirect;
use Helper;

class LoginController extends ApiController
{
    public function index()
    {   
        if(Auth::check()){
            return Redirect::to('/');
        } else {
            $data = [
                'page'        => 'login',
                'askEmail'    => true,
                'askMobile'   => true,
                'user'        => [],
            ];
            if(false){

            }
            else{
                return view('theme.default.pages.login', compact('data'));
            }
        }
    }

    public function userLogin(Request $request)
    {
        try{
            $params = collect($request->all());
            $checklogin = $this->userChecklogin($params);
            if($checklogin){
                //store user cart details after login
                $user = Auth::user();
                $userId = $user->id;
                $macAddr = Helper::getMacAddress();
               
                $loggedOutCarts = Cart::where(['mac_id'=> $macAddr, 'user_id' => null])->latest()->first();
                $loggedInCarts = Cart::where('user_id', $userId)->get();

                if(!empty($loggedOutCarts))
                {
                    foreach($loggedOutCarts->cart_detail as $key=>$cartDetail){
                        //check product is variant product or not
                        $productId = $cartDetail->product_id;
                        $variantOptionId = $cartDetail->variant_option_id;
                        $quantity = $cartDetail->quantity;
                        $cartDetailId = $cartDetail->id;
                        $cartId = $cartDetail->cart_id;

                        //loop to check cart product is available in logged in cart list
                        if($loggedInCarts->count() > 0){
                            foreach($loggedInCarts as $key1=>$cart1){
                                $found = false; //flag to check product already exist in cart
                                $cartDetail1 = $cart1->cart_detail;
                                foreach($cart1->cart_detail as $cartDetail){
                                    $pId = $cartDetail->product_id;
                                    $cartDetailId1 = $cartDetail->id;
                                    
                                    if($variantOptionId != ''){ //product is variant product
                                        $vOptionId = $cartDetail->variant_option_id;
                                        if( $productId == $pId && $variantOptionId == $vOptionId )
                                        {
                                            $found = true;
                                        }
                                    } else {//simple product
                                        if( $productId == $pId )
                                        {
                                            $found = true;
                                        }
                                    }

                                }


                                if($found){
                                    //if cart item found in cart then update quantity
                                    $cartDetailObj = CartDetail::where('id', $cartDetailId1)->first();
                                    $quantity1 = $cartDetailObj->quantity;  
                                    $cartDetailObj->quantity = $quantity1 + $quantity;
                                    $cartDetailObj->save();

                                    Cart::where('id', $cartId)->forceDelete();
                                    CartDetail::where('id', $cartDetailId)->forceDelete();
                                    
                                } else {
                                    $cartDataObj = Cart::where('id', $cartId)->first();
                                    if(isset($cartDataObj)){
                                        $cartDataObj->user_id = $userId;
                                        $cartDataObj->mac_id = NULL;
                                        $cartDataObj->save();
                                    }
                                }
                            } //end inner for each
                        } else {
                            $cartDataObj = Cart::where('id', $cartId)->first();
                            $cartDataObj->user_id = $userId;
                            $cartDataObj->mac_id = NULL;
                            $cartDataObj->save();
                        }
                    }
                //each loop for cart products those are available bafore logged in
                } //end for each

                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.LOGIN_SUCCESS.code'),
                    __('constants.messages.LOGIN_SUCCESS.msg'),
                     []
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

    public function logout(Request $request)
    {
        Auth::logout();
        return Redirect::to('/');
    }
}
