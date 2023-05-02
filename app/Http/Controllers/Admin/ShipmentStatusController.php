<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ShipmentStatus;
use App\Models\ShippingMethod;
use Gate;

class ShipmentStatusController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('shipment_status_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            try{
                    $query = ShipmentStatus::with('shipping_method')->select(sprintf('%s.*', (new ShipmentStatus())->table));
                    $table = Datatables::of($query);

                    $table->addColumn('actions', '&nbsp;');

                    $table->editColumn('id', function ($row) {
                        return $row->id ? $row->id : '';
                    });

                    $table->editColumn('description', function ($row) {
                        return $row->description ? $row->description : '';
                    });

                    $table->editColumn('status_code', function ($row) {
                        return $row->status_code ? $row->status_code : '';
                    });

                    $table->addColumn('shipping_method_id', function ($row) {
                        return $row->shipping_method ? $row->shipping_method->title : '';
                    });

                    $table->editColumn('status', function ($row) {
                        return ($row->status || $row->status == 0)  ? ShipmentStatus::STATUS_RADIO[$row->status] : '';
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

        $shipmentstatus = ShipmentStatus::get();
        $shipping_methods = ShippingMethod::get();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.shipmentstatus.title_singular')." ".trans('global.listing') ]];
        return view('admin.shipments.index',compact('breadcrumbs','shipmentstatus','shipping_methods'));
    }

    public function create()
    {
        abort_if(Gate::denies('shipment_status_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.shipments.create');
    }

    public function store(Request $request)
    {
        $shipmentstatus = ShipmentStatus::create($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.SHIPMENTSTATUS_ADDED_SUCCESSFULLY.code'),
            __('constants.messages.SHIPMENTSTATUS_ADDED_SUCCESSFULLY.msg'),
            $shipmentstatus
        );
    }

    public function edit(ShipmentStatus $shipmentstatus)
    {
        abort_if(Gate::denies('shipment_status_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.shipments.edit', compact('shipmentstatus'));
    }

    public function update(Request $request,$id)
    {
        ShipmentStatus::where('id',$id)->update($request->except(['_method']));

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.SHIPMENTSTATUS_UPDATE_SUCCESSFULLY.code'),
            __('constants.messages.SHIPMENTSTATUS_UPDATE_SUCCESSFULLY.msg'),
        );
    }

    // public function show(ShipmentStatus $shipmentstatus)
    // {
    //     abort_if(Gate::denies('shipment_status_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     return view('admin.shipments.show', compact('shipmentstatus'));
    // }

    public function destroy($id)
    {
        abort_if(Gate::denies('shipment_status_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        ShipmentStatus::where('id',$id)->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.SHIPMENTSTATUS_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.SHIPMENTSTATUS_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(Request $request)
    {
        ShipmentStatus::whereIn('id', request('ids'))->delete();
        
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.SHIPMENTSTATUS_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.SHIPMENTSTATUS_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
