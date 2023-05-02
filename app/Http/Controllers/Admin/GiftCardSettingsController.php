<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

use App\Models\GiftCardSetting;
use Gate;
use Auth;
use DB;

class GiftCardSettingsController extends Controller
{
    public function index()
    {
    	abort_if(Gate::denies('gift_card_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    	$userId = Auth::user()->id;
    	$giftCardSettings = GiftCardSetting::where('user_id', $userId)->first();

    	if($giftCardSettings){
	    	$data['giftType'] = $giftCardSettings['type'];
	    	$data['days'] = $giftCardSettings['days'];
	    	$data['option'] = $giftCardSettings['option'];
    	} else {
    		$data['giftType'] = 1;
	    	$data['days'] = 5;
	    	$data['option'] = 3;
    	}
    	$breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')],['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')], ['name' => trans('cruds.giftCardSettings.title')]];
    	
    	return view('admin.settings.giftcards.index', compact('data','breadcrumbs'));
	}

	public function saveGiftCardSettings(Request $request)
	{
		 try
		 {
		 	$params = collect($request->all());
			$giftType = $params['giftType'];
			$days = $params['days'];
			$option = $params['option'];
			$userId = Auth::user()->id;

			//if data exist then data will update else new recored will be created
			$giftCardSettings = GiftCardSetting::where('user_id', $userId)->first();
			if(!$giftCardSettings){
				$giftCardSettings = new GiftCardSetting;
			}

			$giftCardSettings->user_id = $userId;
			$giftCardSettings->type = $giftType;
			if($giftType == 1){ //never expired
				$giftCardSettings->days = NULL;
				$giftCardSettings->option = NULL;
			} else { // expired time set
				$giftCardSettings->days = $days;
				$giftCardSettings->option = $option;
			}
			$giftCardSettings->save();

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.DATA_SAVED_SUCCESSFULLY.code'),
                __('constants.messages.DATA_SAVED_SUCCESSFULLY.msg')
            );
         } catch (Exception $e) {
             return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );

        }

		dd($request->all());
	}
}

