<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\Order;
use Exception;

class ApproveOrderApiController extends ApiController
{
    public function getOrderAdminApprove(Request $request){
        try{
            $params= collect($request->all());
            $objOrder = Order::where('order_nr',$params['order_nr'])->first();
            if(!empty($objOrder)){
                $objOrder->admin_approve = $params['admin_approve'];
                $objOrder->save();
            }
            $url = route('admin.orders.index');
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.ORDER_APPROVED_SUCCESSFULLY.code'),
                __('constants.messages.ORDER_APPROVED_SUCCESSFULLY.msg'),
                 ['url' => $url]
            );
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
}
