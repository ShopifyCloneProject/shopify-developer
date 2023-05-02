<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Gate;
use Auth;
use DB;

class FilesSettingsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('domain_access_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    	$data = [];
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')], ['name' => trans('cruds.settings.files') ]];
    	return view('admin.settings.files.index', compact('data','breadcrumbs'));
	}
}
