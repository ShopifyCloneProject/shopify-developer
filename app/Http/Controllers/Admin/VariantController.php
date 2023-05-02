<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyVariantRequest;
use App\Http\Requests\StoreVariantRequest;
use App\Http\Requests\UpdateVariantRequest;
use App\Models\Variant;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class VariantController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('variant_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Variant::query()->select(sprintf('%s.*', (new Variant())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'variant_show';
                $editGate = 'variant_edit';
                $deleteGate = 'variant_delete';
                $crudRoutePart = 'variant';

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
                return ( $row->status || $row->status == 0 ) ? Variant::STATUS_RADIO[$row->status] : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $breadcrumbs = [['name' => 'Variant']];
        return view('admin.variants.index', compact('breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('variant_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.variants.create');
    }

    public function store(StoreVariantRequest $request)
    {
        $Variant = Variant::create($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.VARIANT_ADDED_SUCCESSFULLY.code'),
            __('constants.messages.VARIANT_ADDED_SUCCESSFULLY.msg'),
            $Variant
        );
    }

    public function edit(Variant $Variant)
    {
        abort_if(Gate::denies('variant_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.variants.edit', compact('Variant'));
    }

    public function update(UpdateVariantRequest $request, Variant $Variant)
    {
        $Variant->update($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.VARIANT_UPDATE_SUCCESSFULLY.code'),
            __('constants.messages.VARIANT_UPDATE_SUCCESSFULLY.msg'),
        );
    }

    public function show(Variant $Variant)
    {
        abort_if(Gate::denies('variant_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.variants.show', compact('Variant'));
    }

    public function destroy(Variant $Variant)
    {
        abort_if(Gate::denies('variant_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $Variant->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.VARIANT_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.VARIANT_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyVariantRequest $request)
    {
        Variant::whereIn('id', request('ids'))->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.VARIANT_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.VARIANT_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
