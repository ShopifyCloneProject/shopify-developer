<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Tracking;
use App\Models\ShippingMethod;
use Gate;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('tracking_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            try{
                    $query = Tracking::with('shipping_method')->select(sprintf('%s.*', (new Tracking())->table));
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

                    $table->editColumn('shipping_method_id', function ($row) {
                        return $row->shipping_method ? $row->shipping_method->title : '';
                    });

                    $table->editColumn('status', function ($row) {
                        return ($row->status || $row->status == 0)  ? Tracking::STATUS_RADIO[$row->status] : '';
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

        $trackings = Tracking::get();
        $shipping_methods = ShippingMethod::get();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.trackings.title_singular')." ".trans('global.listing') ]];
        return view('admin.trackings.index',compact('breadcrumbs','trackings','shipping_methods'));
    }

    public function create()
    {
        abort_if(Gate::denies('tracking_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.trackings.create');
    }

    public function store(Request $request)
    {
        $tracking = Tracking::create($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.TRACKING_ADDED_SUCCESSFULLY.code'),
            __('constants.messages.TRACKING_ADDED_SUCCESSFULLY.msg'),
            $tracking
        );
    }

    public function edit(Tracking $tracking)
    {
        abort_if(Gate::denies('tracking_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.trackings.edit', compact('tracking'));
    }

    public function update(Request $request,$id)
    {
        Tracking::where('id',$id)->update($request->except('_method'));

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.TRACKING_UPDATE_SUCCESSFULLY.code'),
            __('constants.messages.TRACKING_UPDATE_SUCCESSFULLY.msg'),
        );
    }

    // public function show(Tracking $tracking)
    // {
    //     abort_if(Gate::denies('tracking_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     return view('admin.trackings.show', compact('tracking'));
    // }

    public function destroy($id)
    {
        abort_if(Gate::denies('tracking_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Tracking::where('id',$id)->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.TRACKING_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.TRACKING_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(Request $request)
    {
        Tracking::whereIn('id', request('ids'))->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.TRACKING_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.TRACKING_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
