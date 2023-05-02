<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyVariantMediumRequest;
use App\Http\Requests\StoreVariantMediumRequest;
use App\Http\Requests\UpdateVariantMediumRequest;
use App\Models\Product;
use App\Models\ProductVariantOption;
use App\Models\VariantMedium;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class VariantMediaController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('variant_medium_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = VariantMedium::with(['product_variant', 'product'])->select(sprintf('%s.*', (new VariantMedium())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'variant_medium_show';
                $editGate = 'variant_medium_edit';
                $deleteGate = 'variant_medium_delete';
                $crudRoutePart = 'variant-media';

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
            $table->addColumn('product_variant_sku', function ($row) {
                return $row->product_variant ? $row->product_variant->sku : '';
            });

            $table->addColumn('product_title', function ($row) {
                return $row->product ? $row->product->title : '';
            });

            $table->editColumn('src', function ($row) {
                return $row->src ? $row->src : '';
            });
            $table->editColumn('src_alt_text', function ($row) {
                return $row->src_alt_text ? $row->src_alt_text : '';
            });
            $table->editColumn('is_default', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->is_default ? 'checked' : null) . '>';
            });
            $table->editColumn('reorder', function ($row) {
                return $row->reorder ? $row->reorder : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'product_variant', 'product', 'is_default']);

            return $table->make(true);
        }

        $product_variant_options = ProductVariantOption::get();
        $products                = Product::get();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.variantMedium.title_singular')." ".trans('global.listing') ]];
        return view('admin.variantMedia.index', compact('product_variant_options', 'products','breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('variant_medium_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product_variants = ProductVariantOption::all()->pluck('sku', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.variant-media.index'),'name' => trans('cruds.variantMedium.title') ],['name' => trans('locale.Add')." ".trans('cruds.variantMedium.title') ]]; 
        return view('admin.variantMedia.create', compact('product_variants', 'products','breadcrumbs'));
    }

    public function store(StoreVariantMediumRequest $request)
    {
        try {
            $variantMedium = VariantMedium::create($request->all());
            return redirect()->route('admin.variant-media.index')->with('success','Variantmedia added successfully !');
        } catch (\Exception $e) {
            return redirect()->route('admin.variant-media.index')->with('error','Something went wrong !');
        }
    }

    public function edit(VariantMedium $variantMedium)
    {
        abort_if(Gate::denies('variant_medium_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product_variants = ProductVariantOption::all()->pluck('sku', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $variantMedium->load('product_variant', 'product');

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.variant-media.index'),'name' => trans('cruds.variantMedium.title') ],['name' => trans('global.edit')." ".trans('cruds.variantMedium.title') ]];

        return view('admin.variantMedia.edit', compact('product_variants', 'products', 'variantMedium','breadcrumbs'));
    }

    public function update(UpdateVariantMediumRequest $request, VariantMedium $variantMedium)
    {
        try {
            $variantMedium->update($request->all());
            return redirect()->route('admin.variant-media.index')->with('success','Variantmedia updated successfully !');    
        } catch (\Exception $e) {
            return redirect()->route('admin.variant-media.index')->with('error','Something went wrong !');
        }
    }

    public function show(VariantMedium $variantMedium)
    {
        abort_if(Gate::denies('variant_medium_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $variantMedium->load('product_variant', 'product');

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.variant-media.index'),'name' => trans('cruds.variantMedium.title') ],['name' => trans('global.show')." ".trans('cruds.variantMedium.title') ]];

        return view('admin.variantMedia.show', compact('variantMedium','breadcrumbs'));
    }

    public function destroy(variantMedium $variantMedium, $id)
    {
        try {
              abort_if(Gate::denies('variant_medium_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
              variantMedium::where('id',$id)->delete();   
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
            __('constants.messages.VARIANT_MEDIUM_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.VARIANT_MEDIUM_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyVariantMediumRequest $request)
    {
        try {
              variantMedium::whereIn('id', request('ids'))->delete(); 
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
            __('constants.messages.VARIANT_MEDIUM_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.VARIANT_MEDIUM_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
