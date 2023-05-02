<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\AdminSetting;
use Image;
use Auth;
use Gate;
use Config;

class AdminSettingsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('admin_settings_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $authUserId = Auth::user()->id;
        $client_id = Config::get('client_id');
        $objAdminSetting = AdminSetting::where(['client_id'=>$client_id,'user_id'=>$authUserId])->first();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')], ['name' => trans('cruds.adminsettings.title') ]];
        return view('admin.settings.adminsettings.index', compact('breadcrumbs','objAdminSetting'));

    }
    public function addEditSetting(Request $request){
        try
        {
            $authUserId = Auth::user()->id;
            $client_id = Config::get('client_id');
            $params = collect($request->all());
            $objAdminSetting = AdminSetting::where(['client_id'=>$client_id,'user_id'=>$authUserId])->first();
            if(empty($objAdminSetting)){
                $objAdminSetting = new AdminSetting;
            }
            $objAdminSetting->user_id = $authUserId;
            $objAdminSetting->client_id = $client_id;
                        
            if($params['resetIconImage'] == 1){
                $objAdminSetting->icon = null; 
            }
            if($params['resetLogoImage'] == 1){
                $objAdminSetting->logo = null; 
            }

            if($request->hasFile('icon')){
                $path = "public/$client_id";
                $this->checkFolder($path);
                $this->checkFolder($path .'/'."icon");
                $iconImage = $request->file('icon');
                $refrence_id = mt_rand( 1000, 9999);
                $imagename = time().$refrence_id.".".$iconImage->getClientOriginalExtension();
                Storage::disk('public')->put("$client_id/icon/$imagename", File::get($iconImage),'public');
                $objAdminSetting->icon = $imagename; 
            }
                $objAdminSetting->title = $params['title'];

                if($request->hasFile('logo')){
                    $path = "public/$client_id";
                    $this->checkFolder($path);
                    $this->checkFolder($path .'/'."logo");
                    $logoImage = $request->file('logo');
                    $refrence_id = mt_rand( 1000, 9999);
                    $imagename = time().$refrence_id.".".$logoImage->getClientOriginalExtension();
                    Storage::disk('public')->put("$client_id/logo/$imagename", File::get($logoImage),'public');
                    $image = Image::make(storage_path("app/public/".$client_id."/logo/$imagename"))->resize(120, 61);
                    $image->save(storage_path("app/public/".$client_id."/logo/120/$imagename"));
                    $objAdminSetting->logo = $imagename; 
                }
                $objAdminSetting->save();
                return redirect()->route('admin.settings.adminsettings')->with('success','Admin Settings saved successfully!!');
        }
            
        catch (Exception $e){
                return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }
}
