<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Models\CheckoutSetting;
use Gate;
use Auth;
use DB;
use Exception;

class CheckoutSettingsController extends Controller
{
	public function index()
    {	
    	abort_if(Gate::denies('checkout_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    	$objData = [];
    	$objCheckoutSettings = CheckoutSetting::get();
    	foreach ($objCheckoutSettings as $key => $value) {
    		$finalvalue = $value['data'];
    		if(in_array($value['title'], ['shoppingUpdate1','shoppingUpdate2','tippingOption','orderProcessing1','orderProcessing2','orderFullfilled','emailOption1','emailOption2','abandonCheckout']))	
    		{
    			$finalvalue = ($finalvalue=="true")?true:false;
    		}    	 	
    		$objData[$value['title']] = $finalvalue;
    	}

    	 $data = [
            'settings' => $objData,
         ];
         $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')],['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')], ['name' => trans('cruds.checkoutSettings.customer') . ' ' . trans('cruds.checkoutSettings.title')]];
         
    	return view('admin.settings.checkout.index', compact('data','breadcrumbs'));
	}


	public function handleSettings(Request $request)
	{
	    try {
	    	 $params = collect($request->all());
	    	 foreach ($params as $key => $value) {
	    	 	$finalvalue = $value;
	    	 	if(in_array($key, ['shoppingUpdate1','shoppingUpdate2','tippingOption','orderProcessing1','orderProcessing2','orderFullfilled','emailOption1','emailOption2','abandonCheckout']))	
	    		{
	    			$finalvalue = ($finalvalue==true)?"true":"false";
	    		}    
	    	 	CheckoutSetting::where('title', $key)->update(['data' => $finalvalue]);
	    	 }

	     return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.CHECKOUT_SETTINGS_SET_SUCCESFULLY.code'),
                __('constants.messages.CHECKOUT_SETTINGS_SET_SUCCESFULLY.msg'),
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
