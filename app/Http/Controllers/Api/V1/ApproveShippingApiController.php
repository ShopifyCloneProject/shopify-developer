<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\Shipping;
use Exception;

class ApproveShippingApiController extends ApiController
{
    public function getShippingAdminApprove(Request $request){
        try{
            $params= collect($request->all());
            $objShipping = Shipping::where('id',$params['shippingOrderId'])->first();
            if(!empty($objShipping)){
                $objShipping->admin_approve = $params['admin_approve'];
                $objShipping->save();
            }
            $url = route('admin.shippingproducts.index');
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.SHIPPING_APPROVE_SUCCESSFULLY.code'),
                __('constants.messages.SHIPPING_APPROVE_SUCCESSFULLY.msg'),
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
