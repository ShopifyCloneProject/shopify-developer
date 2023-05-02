<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWeightmanageRequest;
use App\Http\Requests\StoreWeightmanageRequest;
use App\Http\Requests\UpdateWeightmanageRequest;
use App\Models\Weightmanage;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class WeightmanageController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('weightmanage_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Weightmanage::query()->select(sprintf('%s.*', (new Weightmanage())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'weightmanage_show';
                $editGate = 'weightmanage_edit';
                $deleteGate = 'weightmanage_delete';
                $crudRoutePart = 'weightmanages';

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
                return ($row->status || $row->status == 0)  ? Weightmanage::STATUS_RADIO[$row->status] : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.weightmanage.title_singular')." ".trans('global.listing') ]];
        return view('admin.weightmanages.index',compact('breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('weightmanage_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.weightmanages.index'),'name' => trans('cruds.weightmanage.title') ],['name' => trans('locale.Add')." ".trans('cruds.weightmanage.title_singular') ]];   
        return view('admin.weightmanages.create');
    }

    public function store(StoreWeightmanageRequest $request)
    {
        $weightmanage = Weightmanage::create($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.WEIGHT_ADDED_SUCCESSFULLY.code'),
            __('constants.messages.WEIGHT_ADDED_SUCCESSFULLY.msg'),
            $weightmanage
        );
    }

    public function edit(Weightmanage $weightmanage)
    {
        abort_if(Gate::denies('weightmanage_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.weightmanages.edit', compact('weightmanage'));
    }

    public function update(UpdateWeightmanageRequest $request, Weightmanage $weightmanage)
    {
        $weightmanage->update($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.WEIGHT_UPDATE_SUCCESSFULLY.code'),
            __('constants.messages.WEIGHT_UPDATE_SUCCESSFULLY.msg'),
        );
    }

    public function show(Weightmanage $weightmanage)
    {
        // abort_if(Gate::denies('weightmanage_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.weightmanages.show', compact('weightmanage'));
    }

    public function destroy(Weightmanage $weightmanage)
    {
        abort_if(Gate::denies('weightmanage_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $weightmanage->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.WEIGHT_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.WEIGHT_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyWeightmanageRequest $request)
    {
        Weightmanage::whereIn('id', request('ids'))->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.WEIGHT_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.WEIGHT_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
