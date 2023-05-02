<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyInventoryStockRequest;
use App\Http\Requests\StoreInventoryStockRequest;
use App\Http\Requests\UpdateInventoryStockRequest;
use App\Models\Address;
use App\Models\InventoryStock;
use App\Models\ProductVariantOption;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

use Auth;

class InventoryStocksController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('inventory_stock_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = InventoryStock::with(['product_variant_option', 'address', 'product'])
            ->select(sprintf('%s.*', (new InventoryStock())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'inventory_stock_show';
                $editGate = 'inventory_stock_edit';
                $deleteGate = 'inventory_stock_delete';
                $crudRoutePart = 'inventory-stocks';

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
                if($row->product_variant_option_id){
                    $title = $row->product ? $row->product->title : '-';
                    $variantName = $row->product_variant_option ? $row->product_variant_option->variant_name : '-';

                    return sprintf(
                        '<div>%s</br><span class="small">%s</span></div>',
                        $title,
                        $variantName
                    );

                } else {
                    return $row->product ? $row->product->title : '-';
                }
            });
            $table->editColumn('sku', function ($row) {
                if($row->product_variant_option_id){
                    return $row->product_variant_option ? $row->product_variant_option->sku != '' ?  $row->product_variant_option->sku : 'No SKU' : 'No SKU';
                } else {
                    return $row->product ? $row->product->sku != '' ? $row->product->sku : 'No SKU' : 'No SKU';
                }
            });
            $table->editColumn('is_continue_selling', function ($row) {
                if($row->product_variant_option_id){
                    return $row->product_variant_option ? $row->product_variant_option->is_continue_selling == 1 ? 'Continue Selling' : 'Stop Selling' : 'Stop Selling';
                } else {
                    return $row->product ? $row->product->is_continue_selling == 1 ? 'Continue Selling' : 'Stop Selling' : 'Stop Selling';
                }
            });
            $table->editColumn('quantity', function ($row) {
                return $row->quantity ? $row->quantity : '';
            });
            $table->editColumn('available_quantity', function ($row) {
                return $row->available_quantity;
            });
            $table->editColumn('incoming', function ($row) {
                return $row->incoming;
            });
            $table->editColumn('defect_quantity', function ($row) {
                return $row->defect_quantity ? $row->defect_quantity : '';
            });
            $table->addColumn('product_variant_option_sku', function ($row) {
                return $row->product_variant_option ? $row->product_variant_option->sku : '';
            });

            $table->addColumn('address_address', function ($row) {
                return $row->address ? $row->address->address : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'product_variant_option', 'address', 'title', 'sku', 'is_continue_selling']);

            return $table->make(true);
        }

        $product_variant_options = ProductVariantOption::get();
        $addresses = Address::select('id', 'location_name', 'is_default')->where('user_id', Auth::user()->id)->get();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.inventoryStock.title')." ".trans('global.listing') ]];
        return view('admin.inventoryStocks.index', compact('product_variant_options', 'addresses','breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('inventory_stock_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product_variant_options = ProductVariantOption::all()->pluck('sku', 'id')->prepend(trans('global.pleaseSelect'), '');

        $addresses = Address::all()->pluck('address', 'id')->prepend(trans('global.pleaseSelect'), '');

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.products.index'),'name' => trans('cruds.inventoryStock.title')." ".trans('global.listing')],['name' => trans('global.add')." ".trans('cruds.inventoryStock.title_singular') ]];
        return view('admin.inventoryStocks.create', compact('product_variant_options', 'addresses','breadcrumbs'));
    }

    public function store(StoreInventoryStockRequest $request)
    {
        $inventoryStock = InventoryStock::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $inventoryStock->id]);
        }

        return redirect()->route('admin.inventory-stocks.index');
    }

    public function edit(InventoryStock $inventoryStock)
    {
        abort_if(Gate::denies('inventory_stock_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product_variant_options = ProductVariantOption::all()->pluck('sku', 'id')->prepend(trans('global.pleaseSelect'), '');

        $addresses = Address::all()->pluck('address', 'id')->prepend(trans('global.pleaseSelect'), '');

        $inventoryStock->load('product_variant_option', 'address');

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.products.index'),'name' => trans('cruds.inventoryStock.title')." ".trans('global.listing')],['name' => trans('global.edit')." ".trans('cruds.inventoryStock.title_singular') ]];

        return view('admin.inventoryStocks.edit', compact('product_variant_options', 'addresses', 'inventoryStock','breadcrumbs'));
    }

    public function update(UpdateInventoryStockRequest $request, InventoryStock $inventoryStock)
    {
        $inventoryStock->update($request->all());

        return redirect()->route('admin.inventory-stocks.index');
    }

    public function show(InventoryStock $inventoryStock)
    {
        abort_if(Gate::denies('inventory_stock_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $inventoryStock->load('product_variant_option', 'address');

        return view('admin.inventoryStocks.show', compact('inventoryStock'));
    }

    public function destroy(InventoryStock $inventoryStock)
    {
        abort_if(Gate::denies('inventory_stock_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $inventoryStock->delete();

        return back();
    }

    public function massDestroy(MassDestroyInventoryStockRequest $request)
    {
        InventoryStock::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('inventory_stock_create') && Gate::denies('inventory_stock_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new InventoryStock();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function manageInventory(Request $request){
        try{
            $params = collect($request->all());
            $id = $params['id'];
            $qty = $params['qty'];

            InventoryStock::where('id', $id)->update(['available_quantity' => $qty]);

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.DATA_SAVED_SUCCESSFULLY.code'),
                __('constants.messages.DATA_SAVED_SUCCESSFULLY.msg')
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
}
