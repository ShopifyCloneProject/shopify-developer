<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDimensionRequest;
use App\Http\Requests\StoreDimensionRequest;
use App\Http\Requests\UpdateDimensionRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Dimension;
use Gate;

class DimensionController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('dimensions_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Dimension::query()->select(sprintf('%s.*', (new Dimension())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'dimensions_show';
                $editGate = 'dimensions_edit';
                $deleteGate = 'dimensions_delete';
                $crudRoutePart = 'dimensions';

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
            $table->editColumn('short_code', function ($row) {
                return $row->short_code ? $row->short_code : '';
            });
            $table->editColumn('status', function ($row) {
                return ($row->status || $row->status == 0)  ? Dimension::STATUS_RADIO[$row->status] : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.dimensions.title_singular')." ".trans('global.listing') ]];
        return view('admin.dimensions.index',compact('breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('dimensions_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.dimensions.index'),'name' => trans('cruds.dimensions.title') ],['name' => trans('locale.Add')." ".trans('cruds.dimensions.title_singular') ]];   
        return view('admin.dimensions.create',compact('breadcrumbs'));
    }

    public function store(StoreDimensionRequest $request)
    {
        $dimension = Dimension::create($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.DIMENSION_ADDED_SUCCESSFULLY.code'),
            __('constants.messages.DIMENSION_ADDED_SUCCESSFULLY.msg'),
            $dimension
        );
    }

    public function edit(Dimension $dimension)
    {
        abort_if(Gate::denies('dimensions_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.dimensions.index'),'name' => trans('cruds.dimensions.title') ],['name' => trans('locale.Edit')." ".trans('cruds.dimensions.title_singular') ]];
        return view('admin.dimensions.edit', compact('dimension','breadcrumbs'));
    }

    public function update(UpdateDimensionRequest $request, Dimension $dimension)
    {
        $dimension->update($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.DIMENSION_UPDATE_SUCCESSFULLY.code'),
            __('constants.messages.DIMENSION_UPDATE_SUCCESSFULLY.msg'),
        );
    }

    // public function show(Dimension $dimension)
    // {
    //     abort_if(Gate::denies('dimensions_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     return view('admin.dimensions.show', compact('dimension'));
    // }

    public function destroy(Dimension $dimension)
    {
        abort_if(Gate::denies('dimensions_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dimension->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.DIMENSION_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.DIMENSION_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyDimensionRequest $request)
    {
        Dimension::whereIn('id', request('ids'))->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.DIMENSION_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.DIMENSION_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
