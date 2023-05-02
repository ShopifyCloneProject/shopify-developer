<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\ReturnOrder;
use App\Models\ReturnOrderProduct;
use Exception;

class ApproveApiController extends ApiController
{
    public function getAdminApprove(Request $request){
        try{
            $params = collect($request->all());
            $returnOrderId = $params['returnOrderId'];
            $objReturnOrder = ReturnOrder::where('id',$returnOrderId)->first();
            if(!empty($objReturnOrder)){
                $objReturnOrder->admin_approve = $params['admin_approve'];
                $objReturnOrder->save();
                ReturnOrderProduct::where('return_order_id',$returnOrderId)->update(['admin_approve' => $params['admin_approve']]);
            }
           
            $url = url("admin/returnorders");
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
