<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
  // File manager App
  public function index()
  {
  		abort_if(Gate::denies('settings_access') , Response::HTTP_FORBIDDEN, '403 Forbidden');
	    $pageConfigs = [
	      'pageHeader' => false,
	      'contentLayout' => "content-left-sidebar",
	      'pageClass' => 'file-manager-application',
	    ];

    	// return view('/content/apps/fileManager/app-file-manager', ['pageConfigs' => $pageConfigs]);

    	return view('admin.settings.index', compact('pageConfigs'));
  }
}
