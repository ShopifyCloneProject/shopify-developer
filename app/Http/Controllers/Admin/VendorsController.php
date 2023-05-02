<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyVendorRequest;
use App\Http\Requests\StoreVendorRequest;
use App\Http\Requests\UpdateVendorRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Vendor;
use Gate;

class VendorsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('vendor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Vendor::query()->select(sprintf('%s.*', (new Vendor())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'vendor_show';
                $editGate = 'vendor_edit';
                $deleteGate = 'vendor_delete';
                $crudRoutePart = 'vendors';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('status', function ($row) {
                return ( $row->status || $row->status == 0 ) ? Vendor::STATUS_RADIO[$row->status] : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.product.fields.vendor') ]];
        return view('admin.vendors.index', compact('breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('vendor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.vendors.create');
    }

    public function store(StoreVendorRequest $request)
    {
        try {
        $vendor = Vendor::create($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.VENDOR_ADDED_SUCCESSFULLY.code'),
            __('constants.messages.VENDOR_ADDED_SUCCESSFULLY.msg'),
            $vendor
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

    public function edit(Vendor $vendor)
    {
        abort_if(Gate::denies('vendor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.vendors.edit', compact('vendor'));
    }

    public function update(UpdateVendorRequest $request, Vendor $vendor)
    {
        try {
            $vendor->update($request->all());
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.VENDOR_UPDATE_SUCCESSFULLY.code'),
                __('constants.messages.VENDOR_UPDATE_SUCCESSFULLY.msg'),
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

    public function show(Vendor $vendor)
    {
        abort_if(Gate::denies('vendor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.vendors.show', compact('vendor'));
    }

    public function destroy(Vendor $vendor)
    {
        abort_if(Gate::denies('vendor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vendor->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.VENDOR_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.VENDOR_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyVendorRequest $request)
    {
        Vendor::whereIn('id', request('ids'))->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.VENDOR_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.VENDOR_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
