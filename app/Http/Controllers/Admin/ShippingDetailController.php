<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ShippingDetail;
use Auth;
use Gate;
use Config;

class ShippingDetailController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('shipping_details_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data['shippingDetail'] = ShippingDetail::get();  
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')], ['name' => trans('cruds.shippingdetails.title') ]];
        return view('admin.settings.shippingdetails.index', compact('breadcrumbs','data'));

    }                                 

    public function saveShippingDetail(Request $request){
        try{
            $params = collect($request->all());
            foreach($params['shippingdetails'] as $shippingDetail){
                $objShippingDetail = shippingDetail::where('id',$shippingDetail['id'])->first();
                if(empty($objShippingDetail)){
                    $objShippingDetail = new shippingDetail;
                }
                $objShippingDetail->email = $shippingDetail['email'];
                $objShippingDetail->password = $shippingDetail['password'];
                $objShippingDetail->access_token = $shippingDetail['access_token'];
                $objShippingDetail->secret_key = $shippingDetail['secret_key'];
                $objShippingDetail->test_mode = $shippingDetail['test_mode'];
                $objShippingDetail->save();
            }
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.SHIPPING_DETAIL_SAVED_SUCCESSFULLY.code'),
                __('constants.messages.SHIPPING_DETAIL_SAVED_SUCCESSFULLY.msg'),
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


