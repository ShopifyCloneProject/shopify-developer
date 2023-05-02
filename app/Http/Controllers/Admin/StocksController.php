<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyStockRequest;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Models\Address;
use App\Models\Product;
use App\Models\Stock;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class StocksController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('stock_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Stock::with(['product', 'address'])->select(sprintf('%s.*', (new Stock())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'stock_show';
                $editGate = 'stock_edit';
                $deleteGate = 'stock_delete';
                $crudRoutePart = 'stocks';

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
            $table->editColumn('quantity', function ($row) {
                return $row->quantity ? $row->quantity : '';
            });
            $table->editColumn('available_quantity', function ($row) {
                return $row->available_quantity ? $row->available_quantity : '';
            });
            $table->editColumn('defect_quantity', function ($row) {
                return $row->defect_quantity ? $row->defect_quantity : '';
            });
            $table->addColumn('product_title', function ($row) {
                return $row->product ? $row->product->title : '';
            });

            $table->addColumn('address_address', function ($row) {
                return $row->address ? $row->address->address : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'product', 'address']);

            return $table->make(true);
        }

        $products  = Product::get();
        $addresses = Address::get();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.stock.title_singular')." ".trans('global.listing') ]];

        return view('admin.stocks.index', compact('products', 'addresses','breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('stock_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $addresses = Address::all()->pluck('address', 'id')->prepend(trans('global.pleaseSelect'), '');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.stocks.index'),'name' => trans('cruds.stock.title') ],['name' => trans('locale.Add')." ".trans('cruds.stock.title_singular') ]];
        return view('admin.stocks.create', compact('products', 'addresses','breadcrumbs'));
    }

    public function store(StoreStockRequest $request)
    {
        try {
            $stock = Stock::create($request->all());
            return redirect()->route('admin.stocks.index')->with('success','Stock saved successfully!!');
        } catch (Exception $e) {
            return redirect()->route('admin.stocks.index')->with('error','Something went wrong');    
        }
    }

    public function edit(Stock $stock)
    {
        abort_if(Gate::denies('stock_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $addresses = Address::all()->pluck('address', 'id')->prepend(trans('global.pleaseSelect'), '');

        $stock->load('product', 'address');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.stocks.index'),'name' => trans('cruds.stock.title') ],['name' => trans('global.edit')." ".trans('cruds.stock.title_singular') ]];

        return view('admin.stocks.edit', compact('products', 'addresses', 'stock','breadcrumbs'));
    }

    public function update(UpdateStockRequest $request, Stock $stock)
    {
        try {
            $stock->update($request->all());
            return redirect()->route('admin.stocks.index')->with('success','Stock updated successfully!!');
        } catch (Exception $e) {
            return redirect()->route('admin.stocks.index')->with('error','Something went wrong');
        }
    }

    public function show(Stock $stock)
    {
        abort_if(Gate::denies('stock_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $stock->load('product', 'address');
         $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.stocks.index'),'name' => trans('cruds.stock.title') ],['name' => trans('global.show')." ".trans('cruds.stock.title_singular') ]];

        return view('admin.stocks.show', compact('stock','breadcrumbs'));
    }

    public function destroy(Stock $stock,$id)
    {
        try {
              abort_if(Gate::denies('stock_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
              Stock::where('id',$id)->delete();   
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
            __('constants.messages.STOCKS_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.STOCKS_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyStockRequest $request)
    {
        try {
              Stock::whereIn('id', request('ids'))->delete(); 
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
            __('constants.messages.STOCKS_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.STOCKS_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
