<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Gate;
use App\Models\Pages;
use Auth;
use DB;;

class LegalSettingsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('legal_policy_access_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $objData = [];
        $objCheckoutSettings = Pages::get();
        foreach ($objCheckoutSettings as $key => $value) {        
            $objData[$value['pages']] = $value['default'];
        }
        $data = [
            'pages' => $objData,
         ];
    	$breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')], ['name' => trans('cruds.legalSettings.legal_pages') ]];
    	return view('admin.settings.legal.index', compact('data','breadcrumbs'));
	}

    public function handlePages(Request $request)
    {
        try {
             $params = collect($request->all());
             foreach ($params as $key => $value) {
                Pages::where('pages', $key)->update(['data' => $value]);
             }

         return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PAGES_SETTINGS_SET_SUCCESFULLY.code'),
                __('constants.messages.PAGES_SETTINGS_SET_SUCCESFULLY.msg'),
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
