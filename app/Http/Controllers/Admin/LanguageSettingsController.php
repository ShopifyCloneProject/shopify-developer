<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLanguageRequest;
use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Http\Requests\StoreLanguageSelectRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Language;
use App\Models\LanguageSelection;
use App\Models\User;
use Gate;
use Auth;
use Config;


class LanguageSettingsController extends Controller
{
	public function index(Request $request)
    {
    	abort_if(Gate::denies('languages_settings_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    	 if ($request->ajax()) {
            $query = Language::query()->select(sprintf('%s.*', (new Language())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'language_show';
                $editGate = 'language_edit';
                $deleteGate = 'language_delete';
                $crudRoutePart = 'languages';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('status', function ($row) {
                return ( $row->status || $row->status == 0 ) ? Language::STATUS_RADIO[$row->status] : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
 		$languages = Language::get(); 
    	$breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')],['name' => trans('cruds.settings.store_language')]];
    	return view('admin.languages.index', compact('languages','breadcrumbs'));
	}

	public function create()
    {
        abort_if(Gate::denies('languages_settings_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.languages.create');
    }

    public function store(StoreLanguageRequest $request)
    {
    	try{
	        $languages = Language::create($request->all());
	        return $this->successResponse(
	            __('constants.SUCCESS_STATUS'),
	            __('constants.messages.LANGUAGE_ADDED_SUCCESSFULLY.code'),
	            __('constants.messages.LANGUAGE_ADDED_SUCCESSFULLY.msg'),
	            $languages
	        );
    	}
    	catch (\Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }	
    }

    public function edit(Language $languages,$id)
    {
        abort_if(Gate::denies('languages_settings_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $languages = Language::where('id', $id)->first();
        return view('admin.languages.edit', compact('languages'));
    }

    public function update(UpdateLanguageRequest $request, Language $languages, $id)
    {
    	try{
    	$languages = Language::where('id',$id)->update($request->except(['_method']));
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.LANGUAGE_UPDATE_SUCCESSFULLY.code'),
            __('constants.messages.LANGUAGE_UPDATE_SUCCESSFULLY.msg'),
        );
    	}
    	catch (\Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function destroy(Language $languages, $id)
    {
        abort_if(Gate::denies('languages_settings_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Language::where('id',$id)->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.LANGUAGE_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.LANGUAGE_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyLanguageRequest $request)
    {
        Language::whereIn('id', request('ids'))->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.LANGUAGE_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.LANGUAGE_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function selectLanguage(Request $request)
    {
            abort_if(Gate::denies('languages_selection_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $authUser = Config::get('client_id');
            $list['language'] = Language::get();
            $list['selected_language'] = LanguageSelection::where('user_id',$authUser)->first();
            $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => 'Home'], ['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')],['name' => 'Language listing' ]];
            return view('admin.settings.languages.selectlanguage', compact('list','breadcrumbs'));
    }

    public function storeUpdateSelectLanguage(StoreLanguageSelectRequest $request){
        try{
              $authUser = Config::get('client_id');
              $objLanguageSelection = LanguageSelection::where('user_id',$authUser)->first();
                if(empty($objLanguageSelection))
                {
                   $objLanguageSelection = new LanguageSelection;
                }
                  $objLanguageSelection->user_id = $authUser;
                  $objLanguageSelection->language_id = $request->language_id;
                  $objLanguageSelection->save();
            }catch (\Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.LANGUAGE_SELECTED_SUCCESSFULLY.code'),
            __('constants.messages.LANGUAGE_SELECTED_SUCCESSFULLY.msg'),
        );
    }


}
