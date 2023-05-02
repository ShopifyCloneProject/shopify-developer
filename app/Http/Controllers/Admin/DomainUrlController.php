<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Domain;
use Gate;
use Config;

class DomainUrlController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('domain_access_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data = [];
        $authUser = Config::get('client_id');
        $domainInfo = Domain::where('user_id',$authUser)->first();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')], ['name' => trans('cruds.domain.domain_url_settings') ]];
        return view('admin.settings.domain.domainsettings', compact('data','breadcrumbs','domainInfo'));
    }
    public function addEditDomain(Request $request){
        try {
              $authUser = Config::get('client_id');
              $request['user_id'] = $authUser;
              $domainInfo = Domain::where('user_id',$authUser)->first();
              if(empty($domainInfo)){
                  $domainInfo = Domain::create($request->all());
                }
                $domainInfo = Domain::where('user_id',$authUser)->update($request->except(['_method','_token']));
                return redirect()->route('admin.settings.domainurl')->with('success','Domain Settings updated successfully!!');
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