<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;

use Auth;
use Hash;
use Exception;

class AccountController extends Controller
{
    public function index()
    {
        $user = $this->checkAuthUser();
        $data = [
            'page'        => 'account',
            'section'     => 'details',
            'user'        => $user,
        ];
        if(false){

        }
        else{
            return view('theme.default.pages.account', compact('data'));
        }
    }

    public function changepasswordpage()
    {
        $user = $this->checkAuthUser();
        $data = [
            'page'        => 'account',
            'section'     => 'changepass',
            'user'        => $user,
        ];
        if(false){

        }
        else{
            return view('theme.default.pages.account', compact('data'));
        }
    }

    public function addresses()
    {
        $user = $this->checkAuthUser();
        $data = [
            'page'        => 'account',
            'section'     => 'addresses',
            'user'        => $user,
        ];
        if(false){

        }
        else{
            return view('theme.default.pages.account', compact('data'));
        }
    }

    public function getAddresses(){
        try{
            $user = $this->checkAuthUser();
            $Address = Address::where('user_id', $user->id)->whereStatus(0)->get();
            $addresses = [];
            foreach ($Address as $key => $address) {
                $addresses[$key]['id'] =  $address->id;
                $addresses[$key]['locationName'] =  $address->location_name;
                $addresses[$key]['address1'] =  $address->address;
                $addresses[$key]['address2'] =  $address->address_2;
                $addresses[$key]['phone'] =  $address->mobile;
                $addresses[$key]['email'] =  $address->email;
                $addresses[$key]['city'] =  $address->city_name;
                $addresses[$key]['country'] =  $address->country_id;
                $addresses[$key]['shortCode'] =  ($address->country) ? $address->country->short_code : '';
                $addresses[$key]['state'] =  $address->state_id;
                $addresses[$key]['stateName'] = ($address->state) ? $address->state->name : '';
                $addresses[$key]['pincode'] =  $address->postal_code;
                $addresses[$key]['is_default'] =  $address->is_default;
                $addresses[$key]['phone_code'] =  ($address->country) ? $address->country->phone_code : '';
            }

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.DATA_GET_SET_SUCCESFULLY.code'),
                __('constants.messages.DATA_GET_SET_SUCCESFULLY.msg'),
                $addresses
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

    public function updateAddess(Request $request,$addressId){
        try{
            $params = collect($request->all());
            $user = $this->checkAuthUser();
            $objAddress = Address::where(['user_id'=> $user->id, 'id' => $params['id']])->whereStatus(0)->first();
            $objAddress->address = $params['address1'];
            $objAddress->address_2 = $params['address2'];
            $objAddress->city_name = $params['city'];
            $objAddress->postal_code = $params['pincode'];
            $objAddress->save();
            // return redirect('/addresses')->with('message', __('constants.messages.ADDRESS_UPDATED_SUCCESFULLY.msg'));
            if ($request->ajax()) {
            $url = url("/addresses");
                return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.ADDRESS_UPDATED_SUCCESFULLY.code'),
                __('constants.messages.ADDRESS_UPDATED_SUCCESFULLY.msg'),
                ['url'=>$url]
                );
            }
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

    public function removeAddress($id){
        try{
            $userId = Auth::user()->id;
            $address = Address::where('id', $id)->where('user_id', $userId)->first();
            if($address){
               $address->delete();
            }

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.ADDRESS_REMOVE_SUCCESFULLY.code'),
                __('constants.messages.ADDRESS_REMOVE_SUCCESFULLY.msg'),
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

    public function thankyou()
    {   
        $user = $this->checkAuthUser();
        $id = $_REQUEST['oid'];
        $order = Order::where('id', $id)->first();
        $orderData = [];

        if(!empty($order)){

            $orderData = $order->order_products->map(function($items){
                  $data['id'] = $items->product_id;
                  $data['quantity'] = $items->quantity;
                  return $data;
                }
            );

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
            // $diffMinutes = $this->getDifferenceInMinutes($order->updated_at, now());
            // if($diffMinutes < 1 && $order->financial_status != 'failed'){
            //     $message = 'Your order has successfull.';
            //     $this->flash_message('success', $message);
            //     $orderData = $order->order_products->map(function($items){
            //           $data['id'] = $items->product_id;
            //           $data['quantity'] = $items->quantity;
            //           return $data;
            //         }
            //     );
            // } else {
            //     return redirect('/');
            // }

            
        }

        $data = [
            'page'        => 'thankyou',
            'user'        => $user,
            'order'       => $order,
            'orderData'   => $orderData->toArray()
        ];
        if(false){

        }
        else{
            return view('theme.default.pages.thankyou', compact('data'));
        }
    }

    public function updateUser(Request $request)
    {
        try{
            $params = collect($request->all());
            $user = Auth::user();
            $user->name = trim($params['firstname']);
            $user->last_name = trim($params['last_name']);
            if(isset($params['email']) &&  $params['email'] != null && $params['email']!= '' )
            {
                $user->email = trim($params['email']);
            }
            if(isset($params['mobile']) &&  $params['mobile'] != null && $params['mobile']!= '')
            {
                $user->mobile = trim($params['mobile']);
            }
            $user->save();
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.USER_DATA_SUCCESS.code'),
                __('constants.messages.USER_DATA_SUCCESS.msg'),
                 $user
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

    public function changePassword(Request $request)
    {
        try{
            $params = collect($request->all());
            $user = Auth::user();

            if(!$user->sociallogin)
            {
                if (!Hash::check($params['password'], $user->password)) {
                     return $this->errorResponse(
                        __('constants.ERROR_STATUS'),
                        __('constants.errors.BACKEND_OLD_PASSWORD_WRONG.code'),
                        __('constants.errors.BACKEND_OLD_PASSWORD_WRONG.msg')
                    );
                }
            }
            $user->password = bcrypt($params['newpassword']);
            $user->save();
                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.PASSWORD_UPDATE_SUCCESS.code'),
                    __('constants.messages.PASSWORD_UPDATE_SUCCESS.msg')
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
