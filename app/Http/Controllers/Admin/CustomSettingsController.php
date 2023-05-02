<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Gate;
use App\Models\CustomSetting;

class CustomSettingsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('custom_settings_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $customSetting = CustomSetting::all()->pluck('value', 'type');
        $data = $customSetting;
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')], ['name' => trans('global.add')." ".trans('cruds.settings.custom_settings')]];
        return view('admin.settings.custom.index', compact('data','breadcrumbs'));
    }   

    public function store(Request $request)
    {
        try {
             $params = collect($request->all());
             foreach ($params as $type => $value) {
                $customSetting = CustomSetting::where('type', $type)->first();
                if(!$customSetting){
                    $customSetting = new CustomSetting();
                }

                $customSetting->type = $type;
                $customSetting->value = $value;
                $customSetting->save();
             }

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.CUSTOM_SETTINGS_SAVE_SUCCESSFULLY.code'),
                __('constants.messages.CUSTOM_SETTINGS_SAVE_SUCCESSFULLY.msg')
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
