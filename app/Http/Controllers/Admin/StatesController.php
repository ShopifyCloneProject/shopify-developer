<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyStateRequest;
use App\Http\Requests\StoreStateRequest;
use App\Http\Requests\UpdateStateRequest;
use App\Models\Country;
use App\Models\State;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class StatesController extends Controller
{
    public function index(Request $request)
    {
       abort_if(Gate::denies('state_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
       
       $params = collect($request->all());
        if ($request->ajax()) {
            $query = State::with(['country'])->select(sprintf('%s.*', (new State())->table));
            $table = Datatables::of($query);

          /*  $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');*/

            $table->editColumn('actions', function ($row) {
                $viewGate = 'state_show';
                $editGate = 'state_edit';
                $deleteGate = 'state_delete';
                $crudRoutePart = 'states';

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
            $table->addColumn('country_name', function ($row) {
                return $row->country ? $row->country->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'country']);

            return $table->make(true);
        }

        $countries = Country::get();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.state.title_singular')." ".trans('global.listing') ]];
        return view('admin.states.index', compact('countries','breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('state_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $countries = Country::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.states.create', compact('countries'));
    }

    public function store(StoreStateRequest $request)
    {
        $state = State::create($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.STATE_ADDED_SUCCESSFULLY.code'),
            __('constants.messages.STATE_ADDED_SUCCESSFULLY.msg'),
            $state
        );
    }

    public function edit(State $state)
    {
        abort_if(Gate::denies('state_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $countries = Country::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $state->load('country');

        return view('admin.states.edit', compact('countries', 'state'));
    }

    public function update(UpdateStateRequest $request, State $state)
    {
        $state->update($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.STATE_UPDATE_SUCCESSFULLY.code'),
            __('constants.messages.STATE_UPDATE_SUCCESSFULLY.msg'),
        );
    }

    public function show(State $state)
    {
        abort_if(Gate::denies('state_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $state->load('country');

        return view('admin.states.show', compact('state'));
    }

    public function destroy(State $state)
    {
        abort_if(Gate::denies('state_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $state->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.STATE_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.STATE_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyStateRequest $request)
    {
        State::whereIn('id', request('ids'))->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.STATE_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.STATE_DELETE_SUCCESSFULLY.msg'),
        );
    }
    
    public function getStates($id)
    {
        try{
            $states = State::where('country_id', $id)->pluck('name', 'id');
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.STATES_GET_SUCCESSFULLY.code'),
                __('constants.messages.STATES_GET_SUCCESSFULLY.msg'),
                $states
            );
         } catch (Exception $e) {
             return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }
}
