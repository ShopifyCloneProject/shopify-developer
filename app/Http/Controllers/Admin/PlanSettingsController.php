<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Gate;
use Auth;
use DB;

class PlanSettingsController extends Controller
{
   	public function index()
    {
    	abort_if(Gate::denies('plan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    	$data = [];
    	$breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')],['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')], ['name' => trans('cruds.planSettings.plan_details')]];

    	return view('admin.settings.plan.index', compact('data','breadcrumbs'));
	}
}
