<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\ReturnShipping;
use Exception;

class ApproveReturnShippingApiController extends ApiController
{
    public function getReturnShippingAdminApprove(Request $request){
        try{
            $params= collect($request->all());
            $objReturnShipping = ReturnShipping::where('id',$params['returnShippingOrderId'])->first();
            if(!empty($objReturnShipping)){
                $objReturnShipping->admin_approve = $params['admin_approve'];
                $objReturnShipping->save();
            }
            $url = route('admin.returnshippingproducts.index');
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.RETURN_SHIPPING_APPROVE_SUCCESSFULLY.code'),
                __('constants.messages.RETURN_SHIPPING_APPROVE_SUCCESSFULLY.msg'),
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
