<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPaymentTypeRequest;
use App\Http\Requests\StorePaymentTypeRequest;
use App\Http\Requests\UpdatePaymentTypeRequest;
use App\Models\PaymentType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PaymentTypeController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('payment_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = PaymentType::query()->select(sprintf('%s.*', (new PaymentType())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'payment_type_show';
                $editGate = 'payment_type_edit';
                $deleteGate = 'payment_type_delete';
                $crudRoutePart = 'payment-types';

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
                return ($row->status || $row->status == 0)  ? PaymentType::STATUS_RADIO[$row->status] : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.paymentType.title_singular')." ".trans('global.listing') ]];
        return view('admin.paymentTypes.index',  compact('breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('payment_type_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.paymentTypes.create');
    }

    public function store(Request $request)
    {
        try{
            $params = collect($request->all());
            $name = $params['name'];
            $status = $params['status'];

            $paymentType = new PaymentType;
            $paymentType->name = $name;
            $paymentType->status = $status;
            $paymentType->save(); 

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PAYMENTTYPE_ADDED_SUCCESSFULLY.code'),
                __('constants.messages.PAYMENTTYPE_ADDED_SUCCESSFULLY.msg')
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

    public function edit(PaymentType $paymentType)
    {
        abort_if(Gate::denies('payment_type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.paymentTypes.edit', compact('paymentType'));
    }

    public function update(UpdatePaymentTypeRequest $request, PaymentType $paymentType)
    {
        try{
            $paymentType->update($request->all());
         
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PAYMENTTYPE_UPDATE_SUCCESSFULLY.code'),
                __('constants.messages.PAYMENTTYPE_UPDATE_SUCCESSFULLY.msg'),
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

    public function show(PaymentType $paymentType)
    {

        return view('admin.paymentTypes.show', compact('paymentType'));
    }

    public function destroy(PaymentType $paymentType)
    {
        abort_if(Gate::denies('payment_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $paymentType->delete();

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PAYMENTTYPE_DELETE_SUCCESSFULLY.code'),
                __('constants.messages.PAYMENTTYPE_DELETE_SUCCESSFULLY.msg'),
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

    public function massDestroy(MassDestroyPaymentTypeRequest $request)
    {   
        try {
            PaymentType::whereIn('id', request('ids'))->delete();

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PAYMENTTYPE_DELETE_SUCCESSFULLY.code'),
                __('constants.messages.PAYMENTTYPE_DELETE_SUCCESSFULLY.msg'),
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
