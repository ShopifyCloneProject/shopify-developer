<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserStoreIndustryRequest;
use App\Http\Requests\StoreUserStoreIndustryRequest;
use App\Http\Requests\UpdateUserStoreIndustryRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use App\Models\UserStoreIndustry;
use Gate;

class UserStoreIndustryController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('user_store_industry_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = UserStoreIndustry::with(['user'])->select(sprintf('%s.*', (new UserStoreIndustry())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'user_store_industry_show';
                $editGate = 'user_store_industry_edit';
                $deleteGate = 'user_store_industry_delete';
                $crudRoutePart = 'user-store-industries';

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

            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('status', function ($row) {
                return UserStoreIndustry::STATUS_RADIO[$row->status];
            });

            $table->rawColumns(['actions', 'placeholder', 'user']);

            return $table->make(true);
        }

        $users = User::get();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.userStoreIndustry.title_singular')." ".trans('global.listing') ]];
        return view('admin.userStoreIndustries.index', compact('users','breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_store_industry_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $users = User::all()->pluck('email', 'id')->prepend(trans('global.pleaseSelect'), '');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.user-store-industries.index'),'name' => trans('cruds.userStoreIndustry.title') ],['name' => trans('locale.Add')." ".trans('cruds.userStoreIndustry.title_singular') ]]; 
        return view('admin.userStoreIndustries.create', compact('users','breadcrumbs'));
    }

    public function store(StoreUserStoreIndustryRequest $request)
    {
        try {
            $userStoreIndustry = UserStoreIndustry::create($request->all());
            return redirect()->route('admin.user-store-industries.index')->with('success','user store industries saved successfully !');
        } catch (Exception $e) {
            return redirect()->route('admin.user-store-industries.index')->with('error','Something went wrong !');
        }
    }

    public function edit(UserStoreIndustry $userStoreIndustry)
    {
        abort_if(Gate::denies('user_store_industry_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::all()->pluck('email', 'id')->prepend(trans('global.pleaseSelect'), '');

        $userStoreIndustry->load('user');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.user-store-industries.index'),'name' => trans('cruds.userStoreIndustry.title') ],['name' => trans('global.edit')." ".trans('cruds.userStoreIndustry.title_singular') ]];
        return view('admin.userStoreIndustries.edit', compact('users', 'userStoreIndustry','breadcrumbs'));
    }

    public function update(UpdateUserStoreIndustryRequest $request, UserStoreIndustry $userStoreIndustry)
    {
        try {
            $userStoreIndustry->update($request->all());
            return redirect()->route('admin.user-store-industries.index')->with('success','user store industries edited successfully !');
        } catch (Exception $e) {
            return redirect()->route('admin.user-store-industries.index')->with('error','Something went wrong !');
        }
    }

    public function show(UserStoreIndustry $userStoreIndustry)
    {
        abort_if(Gate::denies('user_store_industry_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userStoreIndustry->load('user');
         $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.user-store-industries.index'),'name' => trans('cruds.userStoreIndustry.title') ],['name' => trans('global.show')." ".trans('cruds.userStoreIndustry.title_singular') ]];

        return view('admin.userStoreIndustries.show', compact('userStoreIndustry','breadcrumbs'));
    }

    public function destroy(UserStoreIndustry $userStoreIndustry,$id)
    {
        try {
              abort_if(Gate::denies('user_store_industry_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
              UserStoreIndustry::where('id',$id)->delete();   
        } catch (\Exception $e) {
              return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.USERSTORE_INDUSTRY_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.USERSTORE_INDUSTRY_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyUserStoreIndustryRequest $request)
    {
        try {
            UserStoreIndustry::whereIn('id', request('ids'))->delete();
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.USERSTORE_INDUSTRY_DELETE_SUCCESSFULLY.code'),
                __('constants.messages.USERSTORE_INDUSTRY_DELETE_SUCCESSFULLY.msg'),
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }
}
