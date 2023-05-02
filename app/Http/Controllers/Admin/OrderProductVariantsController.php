<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOrderProductVariantRequest;
use App\Http\Requests\StoreOrderProductVariantRequest;
use App\Http\Requests\UpdateOrderProductVariantRequest;
use App\Models\Order;
use App\Models\OrderProductVariant;
use App\Models\ProductVariantOption;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class OrderProductVariantsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('order_product_variant_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = OrderProductVariant::with(['order_detail', 'product_variant'])->select(sprintf('%s.*', (new OrderProductVariant())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'order_product_variant_show';
                $editGate = 'order_product_variant_edit';
                $deleteGate = 'order_product_variant_delete';
                $crudRoutePart = 'order-product-variants';

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
            $table->addColumn('order_detail_receipt_number', function ($row) {
                return $row->order_detail ? $row->order_detail->receipt_number : '';
            });

            $table->addColumn('product_variant_sku', function ($row) {
                return $row->product_variant ? $row->product_variant->sku : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'order_detail', 'product_variant']);

            return $table->make(true);
        }
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.orderProductVariant.title')." ".trans('global.listing') ]];
        return view('admin.orderProductVariants.index',compact('breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('order_product_variant_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order_details = Order::all()->pluck('receipt_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $product_variants = ProductVariantOption::all()->pluck('sku', 'id')->prepend(trans('global.pleaseSelect'), '');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.order-product-variants.index'),'name' => trans('cruds.orderProductVariant.title') ],['name' => trans('locale.Add')." ".trans('cruds.orderProductVariant.title_singular') ]];
        return view('admin.orderProductVariants.create', compact('order_details', 'product_variants','breadcrumbs'));
    }

    public function store(StoreOrderProductVariantRequest $request)
    {
        try {
            $orderProductVariant = OrderProductVariant::create($request->all());
            return redirect()->route('admin.order-product-variants.index')->with('success','Order product variant added successfully!!');
        } catch (Exception $e) {
            return redirect()->route('admin.order-product-variants.index')->with('error','Something went wrong!!');
        }
    }

    public function edit(OrderProductVariant $orderProductVariant)
    {
        abort_if(Gate::denies('order_product_variant_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order_details = Order::all()->pluck('receipt_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $product_variants = ProductVariantOption::all()->pluck('sku', 'id')->prepend(trans('global.pleaseSelect'), '');

        $orderProductVariant->load('order_detail', 'product_variant');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.order-product-variants.index'),'name' => trans('cruds.orderProductVariant.title') ],['name' => trans('global.edit')." ".trans('cruds.orderProductVariant.title_singular') ]];
        return view('admin.orderProductVariants.edit', compact('order_details', 'product_variants', 'orderProductVariant','breadcrumbs'));
    }

    public function update(UpdateOrderProductVariantRequest $request, OrderProductVariant $orderProductVariant)
    {
        try {
            $orderProductVariant->update($request->all());
            return redirect()->route('admin.order-product-variants.index')->with('success','Order product variant updated successfully!!');
        } catch (Exception $e) {
            return redirect()->route('admin.order-product-variants.index')->with('error','Something went wrong!!');
        }
    }

    public function show(OrderProductVariant $orderProductVariant)
    {
        abort_if(Gate::denies('order_product_variant_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orderProductVariant->load('order_detail', 'product_variant');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.order-product-variants.index'),'name' => trans('cruds.orderProductVariant.title') ],['name' => trans('global.show')." ".trans('cruds.orderProductVariant.title_singular') ]];

        return view('admin.orderProductVariants.show', compact('orderProductVariant','breadcrumbs'));
    }

    public function destroy(OrderProductVariant $orderProductVariant,$id)
    {
        try {
              abort_if(Gate::denies('order_product_variant_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
              OrderProductVariant::where('id',$id)->delete();   
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
            __('constants.messages.ORDER_PRODUCTS_VARIANT_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.ORDER_PRODUCTS_VARIANT_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyOrderProductVariantRequest $request)
    {
        try {
              OrderProductVariant::whereIn('id', request('ids'))->delete(); 
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
            __('constants.messages.ORDER_PRODUCTS_VARIANT_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.ORDER_PRODUCTS_VARIANT_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
