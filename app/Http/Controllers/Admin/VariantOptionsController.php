<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyVariantOptionRequest;
use App\Http\Requests\StoreVariantOptionRequest;
use App\Http\Requests\UpdateVariantOptionRequest;
use App\Models\VariantOption;
use App\Models\Variant;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class VariantOptionsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('variant_option_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = VariantOption::with(['variant'])->select(sprintf('%s.*', (new VariantOption())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'variant_option_show';
                $editGate = 'variant_option_edit';
                $deleteGate = 'variant_option_delete';
                $crudRoutePart = 'variant-options';

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
            $table->editColumn('options', function ($row) {
                return $row->options ? $row->options : '';
            });
            $table->editColumn('status', function ($row) {
                return ( $row->status || $row->status == 0 ) ? VariantOption::STATUS_RADIO[$row->status] : '';
            });
            $table->addColumn('variant_title', function ($row) {
                return $row->variant ? $row->variant->title : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'variant']);

            return $table->make(true);
        }

        $breadcrumbs = [['name' => 'Variant'], ['name' => 'Variant options' ]];
        $variants = Variant::all()->pluck('title', 'id');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.variantOption.fields.options')." ".trans('global.listing') ]];
        return view('admin.variantOptions.index', compact('breadcrumbs', 'variants'));
    }

    public function create()
    {
        abort_if(Gate::denies('variant_option_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $variants = Variant::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.variantOptions.create', compact('variants'));
    }

    public function store(StoreVariantOptionRequest $request)
    {
        try {
            $variantOption = VariantOption::create($request->all());
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.VARIANT_OPTION_ADDED_SUCCESSFULLY.code'),
                __('constants.messages.VARIANT_OPTION_ADDED_SUCCESSFULLY.msg'),
                $variantOption
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

    public function edit(VariantOption $variantOption)
    {
        abort_if(Gate::denies('variant_option_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $variants = Variant::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $variantOption->load('variant');

        return view('admin.variantOptions.edit', compact('variants', 'variantOption'));
    }

    public function update(UpdateVariantOptionRequest $request, VariantOption $variantOption)
    {
        try {
            $variantOption->update($request->all());
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.VARIANT_OPTION_UPDATE_SUCCESSFULLY.code'),
                __('constants.messages.VARIANT_OPTION_UPDATE_SUCCESSFULLY.msg'),
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

    public function show(VariantOption $variantOption)
    {
        abort_if(Gate::denies('variant_option_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $variantOption->load('variant');

        return view('admin.variantOptions.show', compact('variantOption'));
    }

    public function destroy(VariantOption $variantOption)
    {
        abort_if(Gate::denies('variant_option_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $variantOption->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.VARIANT_OPTION_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.VARIANT_OPTION_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyVariantOptionRequest $request)
    {
        VariantOption::whereIn('id', request('ids'))->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.VARIANT_OPTION_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.VARIANT_OPTION_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
