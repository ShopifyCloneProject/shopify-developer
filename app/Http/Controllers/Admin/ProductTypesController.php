<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProductTypeRequest;
use App\Http\Requests\StoreProductTypeRequest;
use App\Http\Requests\UpdateProductTypeRequest;
use App\Models\ProductType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ProductTypesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('product_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ProductType::query()->select(sprintf('%s.*', (new ProductType())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'product_type_show';
                $editGate = 'product_type_edit';
                $deleteGate = 'product_type_delete';
                $crudRoutePart = 'product-types';

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
                return ProductType::STATUS_RADIO[$row->status];
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.product.fields.product_type')." ".trans('global.listing') ]];
        return view('admin.productTypes.index',compact('breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('product_type_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.product-types.index'),'name' => trans('cruds.product.fields.product_type') ],['name' => trans('locale.Add')." ".trans('cruds.product.fields.product_type') ]];
        return view('admin.productTypes.create',compact('breadcrumbs'));
    }

    public function store(StoreProductTypeRequest $request)
    {
        try {
              $productType = ProductType::create($request->all());
              return redirect()->route('admin.product-types.index')->with('success','productType added successfully !');
        } catch (Exception $e) {    
            return redirect()->route('admin.product-types.index')->with('error','Something went wrong !');   
        }
    }

    public function edit(ProductType $productType)
    {
        abort_if(Gate::denies('product_type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.product-types.index'),'name' => trans('cruds.product.fields.product_type') ],['name' => trans('global.edit')." ".trans('cruds.product.fields.product_type') ]];
        return view('admin.productTypes.edit', compact('productType','breadcrumbs'));
    }

    public function update(UpdateProductTypeRequest $request, ProductType $productType)
    {
        try {
              $productType->update($request->all());
              return redirect()->route('admin.product-types.index')->with('success','productType updated successfully !');
        } catch (Exception $e) {
              return redirect()->route('admin.product-types.index')->with('error','Something went wrong !'); 
        }
    }

    public function show($product_type)
    {
        abort_if(Gate::denies('product_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $productType = ProductType::where('id', $product_type)->first();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.product-types.index'),'name' => trans('cruds.product.fields.product_type') ],['name' => trans('global.show')." ".trans('cruds.product.fields.product_type') ]];

        return view('admin.productTypes.show', compact('productType','breadcrumbs'));
    }

    public function destroy(ProductType $productType, $id)
    {
        abort_if(Gate::denies('product_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        ProductType::where('id',$id)->delete(); 
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.PRODUCT_TYPE_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.PRODUCT_TYPE_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyProductTypeRequest $request)
    {
        try {
            ProductType::whereIn('id', request('ids'))->delete(); 
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
            __('constants.messages.PRODUCT_TYPE_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.PRODUCT_TYPE_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
