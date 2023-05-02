<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOrderProductRequest;
use App\Http\Requests\StoreOrderProductRequest;
use App\Http\Requests\UpdateOrderProductRequest;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\VariantOption;
use App\Models\User;
use App\Models\Weightmanage;
use App\Models\ProductVariantOption;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class OrderProductController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('order_product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = OrderProduct::with(['order', 'product','user','weightmanage','variant_options'])->select(sprintf('%s.*', (new OrderProduct())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'order_product_show';
                $editGate = 'order_product_edit';
                $deleteGate = 'order_product_delete';
                $crudRoutePart = 'order-products';

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
            $table->addColumn('order_nr', function ($row) {
                return $row->order ? $row->order->order_nr : '';
            });

            $table->addColumn('product_title', function ($row) {
                return $row->product ? $row->product->title : '';
            });

            $table->addColumn('username', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });

            $table->editColumn('mobile', function ($row) {
                return $row->mobile ? $row->mobile : '';
            });

            $table->addColumn('product_variant_options', function ($row) {
                return $row->product_variant_options ? $row->product_variant_options->variant_option_1_id : '';
            });

            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });

            $table->editColumn('slug', function ($row) {
                return $row->slug ? $row->slug : '';
            });

            $table->editColumn('price', function ($row) {
                return $row->price ? $row->price : '';
            });

            $table->editColumn('quantity', function ($row) {
                return $row->quantity ? $row->quantity : '';
            });

            $table->editColumn('sku', function ($row) {
                return $row->sku ? $row->sku : '';
            });

            $table->editColumn('barcode', function ($row) {
                return $row->barcode ? $row->barcode : '';
            });

            $table->addColumn('weightmanage', function ($row) {
                return $row->weightmanage ? $row->weightmanage->title : '';
            });

            $table->editColumn('weight', function ($row) {
                return $row->weight ? $row->weight : '';
            });

            $table->editColumn('hs_code', function ($row) {
                return $row->hs_code ? $row->hs_code : '';
            });

            $table->editColumn('is_product_charge', function ($row) {
                return $row->is_product_charge ? $row->is_product_charge : '';
            });

            $table->editColumn('is_track', function ($row) {
                return $row->is_track ? $row->is_track : '';
            });

            $table->editColumn('is_special_product', function ($row) {
                return $row->is_special_product ? $row->is_special_product : '';
            });

            $table->editColumn('special_price', function ($row) {
                return $row->special_price ? $row->special_price : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'order', 'product','user','weightmanage','variant_options']);

            return $table->make(true);
        }
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.orderProduct.title')." ".trans('global.listing') ]];
        return view('admin.orderProducts.index',compact('breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('order_product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data['orders'] = Order::all()->pluck('order_nr', 'id')->prepend(trans('global.pleaseSelect'), '');
        $data['user'] = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
    
        $data['weightmanages'] = Weightmanage::all()->pluck('title','id')->prepend(trans('global.pleaseSelect'), '');

        $data['products'] = Product::get()->pluck('id','title')->toArray();
        $data['variantProduct'] = Product::select('id')->where('is_product_variant',1)->get()->toArray();
        $data['nonVariantProduct'] = Product::select('id')->where('is_product_variant',0)->get()->toArray();
        $data['variantOptions'] = VariantOption::get()->pluck('options','id');
        $data['product_variant_options'] = ProductVariantOption::get()->groupBy('product_id');

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.order-products.index'),'name' => trans('cruds.orderProduct.title') ],['name' => trans('locale.Add')." ".trans('cruds.orderProduct.title_singular') ]];
        return view('admin.orderProducts.create', compact('data', 'breadcrumbs'));
    }

    public function store(StoreOrderProductRequest $request)
    {
        $orderProduct = OrderProduct::create($request->all());

        return redirect()->route('admin.order-products.index');
    }

    public function edit(OrderProduct $orderProduct)
    {
        abort_if(Gate::denies('order_product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orders = Order::all()->pluck('paid_at', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $orderProduct->load('order', 'product');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.order-products.index'),'name' => trans('cruds.orderProduct.title') ],['name' => trans('global.edit')." ".trans('cruds.orderProduct.title_singular') ]];
        return view('admin.orderProducts.edit', compact('orders', 'products', 'orderProduct','breadcrumbs'));
    }

    public function update(UpdateOrderProductRequest $request, OrderProduct $orderProduct)
    {
        $orderProduct->update($request->all());

        return redirect()->route('admin.order-products.index');
    }

    public function show(OrderProduct $orderProduct)
    {
        abort_if(Gate::denies('order_product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orderProduct->load('order', 'product');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.order-products.index'),'name' => trans('cruds.orderProduct.title') ],['name' => trans('global.show')." ".trans('cruds.orderProduct.title_singular') ]];

        return view('admin.orderProducts.show', compact('orderProduct','breadcrumbs'));
    }

    public function destroy(OrderProduct $orderProduct)
    {
        abort_if(Gate::denies('order_product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orderProduct->delete();

        return back();
    }

    public function massDestroy(MassDestroyOrderProductRequest $request)
    {
        OrderProduct::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
