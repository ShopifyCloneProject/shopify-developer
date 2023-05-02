<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\FrontThemeSetting;
use App\Models\Timing;
use Gate;
use Config;

class ThemeSettingsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('theme_settings_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $userId = Config::get('client_id');
        $data = [];
        $data['objThemesetting'] = FrontThemeSetting::where('user_id',$userId)->first();
        $data['objTiming'] = Timing::where('user_id',$userId)->get();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.themesettings.title') ]];
        return view('admin.settings.themesettings.index', compact('breadcrumbs','data'));

    }
    public function addEditFrontTheme(Request $request){
        try{
            $params = collect($request->all());
            $userId = Config::get('client_id');
            $objThemesetting = FrontThemeSetting::where('user_id',$userId)->first();
            if(empty($objThemesetting)){
                $objThemesetting = new FrontThemeSetting;
            }
            $objThemesetting->user_id = $userId;
            $objThemesetting->facebook = $params['facebook'];
            $objThemesetting->twitter = $params['twitter'];
            $objThemesetting->gplus = $params['gplus'];
            $objThemesetting->instagram = $params['instagram'];
            $objThemesetting->vimeo = $params['vimeo'];
            $objThemesetting->linkedin = $params['linkedin'];
            $objThemesetting->pinterest = $params['pinterest'];
            $objThemesetting->youtube = $params['youtube'];
            $objThemesetting->email = $params['email'];
            $objThemesetting->sitename = $params['sitename'];
            $objThemesetting->save();

            foreach($params['finalTiming'] as $finaltiming){
                $objTiming = Timing::where(['days'=>$finaltiming['days'], 'user_id' => $userId])->first();
                if(empty($objTiming)){
                    $objTiming = new Timing;
                }
                $objTiming->user_id = $userId;
                $objTiming->days = $finaltiming['days'];
                foreach($params['timing'] as $key=>$timing){
                    if($finaltiming['open'] == 0){
                        $objTiming->open = "0.00";
                    }
                    if($finaltiming['close'] == 0){
                        $objTiming->close = "0.00";
                    }
                    if($finaltiming['open'] > 0){
                        $objTiming->open = $params['timing'][$finaltiming['open']];
                    }
                    if($finaltiming['close'] > 0 ){
                        $objTiming->close = $params['timing'][$finaltiming['close']];
                    }
                }
                $objTiming->save();    
            }

            $url = route('admin.themesettings.index');
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.THEME_SETTINGS_SAVED_SUCCESSFULLY.code'),
                __('constants.messages.THEME_SETTINGS_SAVED_SUCCESSFULLY.msg'),
                ['url'=>$url]
            );
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