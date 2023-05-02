<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Gate;
use Auth;
use DB;

class ChannelSettingsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('sales_channel_access_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    	$data = [];
    	$breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')], ['name' => trans('cruds.settings.sales_channel') ]];
    	return view('admin.settings.channel.index', compact('data','breadcrumbs'));
	}
}
