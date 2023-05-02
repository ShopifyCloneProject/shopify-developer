<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ShipOrder;
use App\Models\ShippingMethod;
use Gate;

class ShipOrdersController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('ship_orders_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            try{
                    $query = ShipOrder::with('shipping_method')->select(sprintf('%s.*', (new ShipOrder())->table));
                    $table = Datatables::of($query);

                    $table->addColumn('actions', '&nbsp;');

                    $table->editColumn('id', function ($row) {
                        return $row->id ? $row->id : '';
                    });

                    $table->editColumn('filter', function ($row) {
                        return $row->filter ? $row->filter : '';
                    });

                    $table->editColumn('description', function ($row) {
                        return $row->description ? $row->description : '';
                    });

                    $table->addColumn('shipping_method_id', function ($row) {
                        return $row->shipping_method ? $row->shipping_method->title : '';
                    });

                    $table->editColumn('status', function ($row) {
                        return ($row->status || $row->status == 0)  ? ShipOrder::STATUS_RADIO[$row->status] : '';
                    });

                    $table->rawColumns(['actions','shipping_method']);

                    return $table->make(true);
                }catch (Exception $e) {
                     return $this->errorResponse(
                        __('constants.ERROR_STATUS'),
                        __('constants.errors.SOMETHING_WRONG.code'),
                        __('constants.errors.SOMETHING_WRONG.msg'),
                        $e->getMessage()
                        );
                } 
        }

        $shiporders = ShipOrder::get();
        $shipping_methods = ShippingMethod::get();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.shiporders.title_singular')." ".trans('global.listing') ]];
        return view('admin.shiporders.index',compact('breadcrumbs','shiporders','shipping_methods'));
    }

    public function create()
    {
        abort_if(Gate::denies('ship_orders_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.shiporders.create');
    }

    public function store(Request $request)
    {
        $shiporders = ShipOrder::create($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.SHIPORDERS_ADDED_SUCCESSFULLY.code'),
            __('constants.messages.SHIPORDERS_ADDED_SUCCESSFULLY.msg'),
            $shiporders
        );
    }

    public function edit(ShipOrder $shiporders)
    {
        abort_if(Gate::denies('ship_orders_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.shiporders.edit', compact('shiporders'));
    }

    public function update(Request $request,$id)
    {
        ShipOrder::where('id',$id)->update($request->except(['_method']));

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.SHIPORDERS_UPDATE_SUCCESSFULLY.code'),
            __('constants.messages.SHIPORDERS_UPDATE_SUCCESSFULLY.msg'),
        );
    }

    // public function show(Country $country)
    // {
    //     abort_if(Gate::denies('ship_orders_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     return view('admin.shiporders.show', compact('country'));
    // }

    public function destroy($id)
    {
        abort_if(Gate::denies('ship_orders_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        ShipOrder::where('id',$id)->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.SHIPORDERS_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.SHIPORDERS_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(Request $request)
    {
        ShipOrder::whereIn('id', request('ids'))->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.SHIPORDERS_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.SHIPORDERS_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
