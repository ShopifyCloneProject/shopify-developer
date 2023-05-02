<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ReturnOrder;
use App\Models\ReturnOrderProduct;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ExchangeMedium;
use Auth;
use Config;
use Storage;

class ReturnOrdersController extends ApiController
{
     public function saveReturnOrder(Request $request){
        try {
            $params = collect($request->all());
            $authUser = Auth::user();
            $order_product_id = $params['id'];
            $objReturnOrder = ReturnOrder::where(['order_id'=>$params['order_id'],'admin_approve' => 0])->latest()->first();
            if(empty($objReturnOrder))
            {
                $objReturnOrder = new ReturnOrder;
            }
            $objReturnOrder->order_id = $params['order_id'];
            $objReturnOrder->user_id = $authUser->id;
            $objReturnOrder->save();

            $objReturnOrderProduct = ReturnOrderProduct::where('return_order_id',$objReturnOrder->id)->first();
            $objReturnOrderProduct = new ReturnOrderProduct;
            $objReturnOrderProduct->return_order_id = $objReturnOrder->id;
            $objReturnOrderProduct->product_id = $params['product_id'];
            $objReturnOrderProduct->product_variant_options_id = $params['product_variant_option_id'];
            $objReturnOrderProduct->quantity = $params['quantity'];
            $objReturnOrderProduct->description = $params['description'];
            $objReturnOrderProduct->save();  
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.RETURN_ORDER_SAVED_SUCCESSFULLY.code'),
                __('constants.messages.RETURN_ORDER_SAVED_SUCCESSFULLY.msg'),
            );
            
        }
        catch (Exception $e) 
        {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
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
}
