<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProductVariantOptionRequest;
use App\Http\Requests\StoreProductVariantOptionRequest;
use App\Http\Requests\UpdateProductVariantOptionRequest;
use App\Models\Country;
use App\Models\Product;
use App\Models\ProductVariantOption;
use App\Models\VariantOption;
use App\Models\Weightmanage;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ProductVariantOptionsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('product_variant_option_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ProductVariantOption::with(['product', 'variant_option_1', 'variant_option_2', 'variant_option_3', 'weight_type', 'country'])->select(sprintf('%s.*', (new ProductVariantOption())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'product_variant_option_show';
                $editGate = 'product_variant_option_edit';
                $deleteGate = 'product_variant_option_delete';
                $crudRoutePart = 'product-variant-options';

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
            $table->addColumn('product_title', function ($row) {
                return $row->product ? $row->product->title : '';
            });

            $table->addColumn('variant_option_1_options', function ($row) {
                return $row->variant_option_1 ? $row->variant_option_1->options : '';
            });

            $table->addColumn('variant_option_2_options', function ($row) {
                return $row->variant_option_2 ? $row->variant_option_2->options : '';
            });

            $table->addColumn('variant_option_3_options', function ($row) {
                return $row->variant_option_3 ? $row->variant_option_3->options : '';
            });

            $table->editColumn('src', function ($row) {
                return $row->src ? $row->src : '';
            });
            $table->editColumn('src_alt_text', function ($row) {
                return $row->src_alt_text ? $row->src_alt_text : '';
            });
            $table->editColumn('price', function ($row) {
                return $row->price ? $row->price : '';
            });
            $table->editColumn('compare_at_price', function ($row) {
                return $row->compare_at_price ? $row->compare_at_price : '';
            });
            $table->editColumn('cost_per_item', function ($row) {
                return $row->cost_per_item ? $row->cost_per_item : '';
            });
            $table->editColumn('is_product_charge', function ($row) {
                return ProductVariantOption::IS_PRODUCT_CHARGE_RADIO[$row->is_product_charge];
            });
            $table->editColumn('sku', function ($row) {
                return $row->sku ? $row->sku : '';
            });
            $table->editColumn('barcode', function ($row) {
                return $row->barcode ? $row->barcode : '';
            });
            $table->editColumn('is_track', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->is_track ? 'checked' : null) . '>';
            });
            $table->editColumn('is_continue_selling', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->is_continue_selling ? 'checked' : null) . '>';
            });
            $table->editColumn('is_physical_product', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->is_physical_product ? 'checked' : null) . '>';
            });
            $table->editColumn('weight', function ($row) {
                return $row->weight ? $row->weight : '';
            });
            $table->addColumn('weight_type_title', function ($row) {
                return $row->weight_type ? $row->weight_type->title : '';
            });

            $table->addColumn('country_name', function ($row) {
                return $row->country ? $row->country->name : '';
            });

            $table->editColumn('hs_code', function ($row) {
                return $row->hs_code ? $row->hs_code : '';
            });
            $table->editColumn('is_special_product', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->is_special_product ? 'checked' : null) . '>';
            });
            $table->editColumn('special_price', function ($row) {
                return $row->special_price ? $row->special_price : '';
            });

            $table->editColumn('special_product_status', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->special_product_status ? 'checked' : null) . '>';
            });
            $table->editColumn('is_shipping', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->is_shipping ? 'checked' : null) . '>';
            });
            $table->editColumn('is_taxable', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->is_taxable ? 'checked' : null) . '>';
            });
            $table->editColumn('reorder', function ($row) {
                return $row->reorder ? $row->reorder : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'product', 'variant_option_1', 'variant_option_2', 'variant_option_3', 'is_track', 'is_continue_selling', 'is_physical_product', 'weight_type', 'country', 'is_special_product', 'special_product_status', 'is_shipping', 'is_taxable']);

            return $table->make(true);
        }

        $products        = Product::get();
        $variant_options = VariantOption::get();
        $weightmanages   = Weightmanage::get();
        $countries       = Country::get();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.productVariantOption.title')." ".trans('global.listing') ]];

        return view('admin.productVariantOptions.index', compact('products', 'variant_options', 'weightmanages', 'countries','breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('product_variant_option_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $variant_option_1s = VariantOption::all()->pluck('options', 'id')->prepend(trans('global.pleaseSelect'), '');

        $variant_option_2s = VariantOption::all()->pluck('options', 'id')->prepend(trans('global.pleaseSelect'), '');

        $variant_option_3s = VariantOption::all()->pluck('options', 'id')->prepend(trans('global.pleaseSelect'), '');

        $weight_types = Weightmanage::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $countries = Country::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.product-variant-options.index'),'name' => trans('cruds.productVariantOption.title_singular') ],['name' => trans('locale.Add')." ".trans('cruds.productVariantOption.title_singular') ]];
        
        return view('admin.productVariantOptions.create', compact('products', 'variant_option_1s', 'variant_option_2s', 'variant_option_3s', 'weight_types', 'countries','breadcrumbs'));
    }

    public function store(StoreProductVariantOptionRequest $request)
    {
        try {
              $productVariantOption = ProductVariantOption::create($request->all());
              return redirect()->route('admin.product-variant-options.index')->with('success','Productvariantoption added successfully!!');
        } catch (Exception $e) {
              return redirect()->route('admin.product-variant-options.index')->with('error','Something went wrong');
        }
    }

    public function edit(ProductVariantOption $productVariantOption)
    {
        abort_if(Gate::denies('product_variant_option_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $variant_option_1s = VariantOption::all()->pluck('options', 'id')->prepend(trans('global.pleaseSelect'), '');

        $variant_option_2s = VariantOption::all()->pluck('options', 'id')->prepend(trans('global.pleaseSelect'), '');

        $variant_option_3s = VariantOption::all()->pluck('options', 'id')->prepend(trans('global.pleaseSelect'), '');

        $weight_types = Weightmanage::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $countries = Country::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $productVariantOption->load('product', 'variant_option_1', 'variant_option_2', 'variant_option_3', 'weight_type', 'country');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.product-variant-options.index'),'name' => trans('cruds.productVariantOption.title_singular') ],['name' => trans('global.edit')." ".trans('cruds.productVariantOption.title_singular') ]];

        return view('admin.productVariantOptions.edit', compact('products', 'variant_option_1s', 'variant_option_2s', 'variant_option_3s', 'weight_types', 'countries', 'productVariantOption'));
    }

    public function update(UpdateProductVariantOptionRequest $request, ProductVariantOption $productVariantOption)
    {
        try {
             $productVariantOption->update($request->all());
             return redirect()->route('admin.product-variant-options.index')->with('success','Productvariantoption updated successfully!!');   
        } catch (Exception $e) {
            return redirect()->route('admin.product-variant-options.index')->with('error','Something went wrong');   
            
        }
    }

    public function show(ProductVariantOption $productVariantOption)
    {
        abort_if(Gate::denies('product_variant_option_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productVariantOption->load('product', 'variant_option_1', 'variant_option_2', 'variant_option_3', 'weight_type', 'country');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.product-variant-options.index'),'name' => trans('cruds.productVariantOption.title_singular') ],['name' => trans('global.show')." ".trans('cruds.productVariantOption.title_singular') ]];

        return view('admin.productVariantOptions.show', compact('productVariantOption' ,'breadcrumbs'));
    }

    public function destroy(ProductVariantOption $productVariantOption,$id)
    {
        // abort_if(Gate::denies('product_variant_option_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $productVariantOption->delete();

        // return back();

        try {
              abort_if(Gate::denies('product_variant_option_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
              ProductVariantOption::where('id',$id)->delete();   
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
            __('constants.messages.PRODUCTVARIANT__OPTIONS_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.PRODUCTVARIANT__OPTIONS_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyProductVariantOptionRequest $request)
    {
        // ProductVariantOption::whereIn('id', request('ids'))->delete();

        // return response(null, Response::HTTP_NO_CONTENT);

         try {
              ProductVariantOption::whereIn('id', request('ids'))->delete(); 
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
            __('constants.messages.PRODUCTVARIANT__OPTIONS_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.PRODUCTVARIANT__OPTIONS_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
