<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTimeZoneRequest;
use App\Http\Requests\StoreTimeZoneRequest;
use App\Http\Requests\UpdateTimeZoneRequest;
use App\Models\TimeZone;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TimeZonesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('time_zone_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = TimeZone::query()->select(sprintf('%s.*', (new TimeZone())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'time_zone_show';
                $editGate = 'time_zone_edit';
                $deleteGate = 'time_zone_delete';
                $crudRoutePart = 'time-zones';

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
            $table->editColumn('timezone_value', function ($row) {
                return $row->timezone_value ? $row->timezone_value : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.timeZone.title_singular')." ".trans('global.listing') ]];
        return view('admin.timeZones.index',compact('breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('time_zone_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.time-zones.index'), 'name' => trans('cruds.timeZone.title')], ['name' => trans('locale.Add')." ".trans('cruds.timeZone.title_singular') ]];
        return view('admin.timeZones.create',compact('breadcrumbs'));
    }

    public function store(StoreTimeZoneRequest $request)
    {
        $timeZone = TimeZone::create($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.TIMEZONE_ADDED_SUCCESSFULLY.code'),
            __('constants.messages.TIMEZONE_ADDED_SUCCESSFULLY.msg'),
            $timeZone
        );
    }

    public function edit(TimeZone $timeZone)
    {
        abort_if(Gate::denies('time_zone_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.time-zones.index'),'name' => 'TimeZones' ],['name' => 'Edit TimeZone' ]];
        return view('admin.timeZones.edit', compact('timeZone','breadcrumbs'));
    }

    public function update(UpdateTimeZoneRequest $request, TimeZone $timeZone)
    {
        $timeZone->update($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.TIMEZONE_UPDATE_SUCCESSFULLY.code'),
            __('constants.messages.TIMEZONE_UPDATE_SUCCESSFULLY.msg'),
        );
    }

    public function show(TimeZone $timeZone)
    {
        // abort_if(Gate::denies('time_zone_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.timeZones.show', compact('timeZone'));
    }

    public function destroy(TimeZone $timeZone)
    {
        abort_if(Gate::denies('time_zone_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $timeZone->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.TIMEZONE_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.TIMEZONE_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyTimeZoneRequest $request)
    {
        TimeZone::whereIn('id', request('ids'))->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.TIMEZONE_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.TIMEZONE_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
