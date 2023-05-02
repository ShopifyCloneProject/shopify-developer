<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyShippingMethodRequest;
use App\Http\Requests\StoreShippingMethodRequest;
use App\Http\Requests\UpdateShippingMethodRequest;
use App\Models\ShippingMethod;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ShippingMethodController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('shipping_method_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = ShippingMethod::query()->select(sprintf('%s.*', (new ShippingMethod())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'shipping_method_show';
                $editGate = 'shipping_method_edit';
                $deleteGate = 'shipping_method_delete';
                $crudRoutePart = 'shipping-methods';

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
                return ( $row->status || $row->status == 0 ) ? ShippingMethod::STATUS_RADIO[$row->status] : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('locale.Methods')." ".trans('global.listing') ]];
        return view('admin.shippingMethods.index',compact('breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('shipping_method_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.shipping-methods.index'),'name' => trans('cruds.shippingMethod.title') ],['name' => trans('locale.Add')." ".trans('locale.Method') ]];
        return view('admin.shippingMethods.create',compact('breadcrumbs'));
    }

    public function store(StoreShippingMethodRequest $request)
    {
        $shippingMethod = ShippingMethod::create($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.SHIPPINGMETHOD_ADDED_SUCCESSFULLY.code'),
            __('constants.messages.SHIPPINGMETHOD_ADDED_SUCCESSFULLY.msg'),
            $shippingMethod
        );
    }

    public function edit(ShippingMethod $shippingMethod)
    {
        abort_if(Gate::denies('shipping_method_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.shippingMethods.edit', compact('shippingMethod'));
    }

    public function update(UpdateShippingMethodRequest $request, ShippingMethod $shippingMethod)
    {
        $shippingMethod->update($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.SHIPPINGMETHOD_UPDATE_SUCCESSFULLY.code'),
            __('constants.messages.SHIPPINGMETHOD_UPDATE_SUCCESSFULLY.msg'),
        );
    }

    public function show(ShippingMethod $shippingMethod)
    {
        // abort_if(Gate::denies('shipping_method_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.shippingMethods.show', compact('shippingMethod'));
    }

    public function destroy(ShippingMethod $shippingMethod)
    {
        abort_if(Gate::denies('shipping_method_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shippingMethod->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.SHIPPINGMETHOD_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.SHIPPINGMETHOD_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyShippingMethodRequest $request)
    {
        ShippingMethod::whereIn('id', request('ids'))->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.SHIPPINGMETHOD_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.SHIPPINGMETHOD_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
