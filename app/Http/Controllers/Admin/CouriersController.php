<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Courier;
use App\Models\ShippingMethod;
use Gate;

class CouriersController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('couriers_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            try{
                    $query = Courier::with(['shipping_method'])->select(sprintf('%s.*', (new Courier())->table));
                    $table = Datatables::of($query);

                    $table->addColumn('actions', '&nbsp;');

                    $table->editColumn('id', function ($row) {
                        return $row->id ? $row->id : '';
                    });

                    $table->editColumn('name', function ($row) {
                        return $row->name ? $row->name : '';
                    });

                    $table->editColumn('courier_code', function ($row) {
                        return $row->courier_code ? $row->courier_code : '';
                    });

                    $table->editColumn('shipping_method_id', function ($row) {
                        return $row->shipping_method ? $row->shipping_method->title : '';
                    });

                    $table->editColumn('status', function ($row) {
                        return ($row->status || $row->status == 0)  ? Courier::STATUS_RADIO[$row->status] : '';
                    });

                    $table->rawColumns(['actions','shipping_method_id']);

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

        $couriers = Courier::get();
        $shipping_methods = ShippingMethod::get();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.couriers.title_singular')." ".trans('global.listing') ]];
        return view('admin.couriers.index',compact('breadcrumbs','couriers','shipping_methods'));
    }

    public function create()
    {
        abort_if(Gate::denies('couriers_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.couriers.create');
    }

    public function store(Request $request)
    {
        $couriers = Courier::create($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.COURIER_ADDED_SUCCESSFULLY.code'),
            __('constants.messages.COURIER_ADDED_SUCCESSFULLY.msg'),
            $couriers
        );
    }

    public function edit(Courier $couriers)
    {
        abort_if(Gate::denies('couriers_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $shipping_methods = ShippingMethod::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');
        // $couriers->load('shipping_method');
        return view('admin.couriers.edit', compact('couriers','shipping_methods'));
    }

    public function update(Request $request, Courier $couriers,$id)
    {
        $couriers = Courier::where('id',$id)->update($request->except(['_method']));
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.COURIER_UPDATE_SUCCESSFULLY.code'),
            __('constants.messages.COURIER_UPDATE_SUCCESSFULLY.msg'),
            $couriers
        );
    }

    public function destroy(Courier $couriers,$id)
    {
        try {
              abort_if(Gate::denies('couriers_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
              Courier::where('id',$id)->delete();   
              return $this->successResponse(
              __('constants.SUCCESS_STATUS'),
              __('constants.messages.COURIER_DELETE_SUCCESSFULLY.code'),
              __('constants.messages.COURIER_DELETE_SUCCESSFULLY.msg'),
                );
        } catch (\Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function massDestroy(Request $request)
    {
        Courier::whereIn('id', request('ids'))->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.COURIER_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.COURIER_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
