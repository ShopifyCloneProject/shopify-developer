<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPaymentMethodRequest;
use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;
use App\Models\PaymentMethod;
use App\Models\PaymentType;
use App\Models\MethodType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PaymentMethodController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('payment_method_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = PaymentMethod::query()->select(sprintf('%s.*', (new PaymentMethod())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'payment_method_show';
                $editGate = 'payment_method_edit';
                $deleteGate = 'payment_method_delete';
                $crudRoutePart = 'payment-methods';

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
                return ($row->status || $row->status == 0)  ? PaymentMethod::STATUS_RADIO[$row->status] : '';
            });

            $table->editColumn('types', function ($row) {
                $string = '';
                    $types = $row->types;
                    foreach ($types as $key => $value) {
                        $string .=  '<div class="badge badge-primary mr-1 mb-1">'.$value->name.'</div>';
                    }

                    if($string != ''){
                        return sprintf(
                            '<div class="conditionx-list">'.$string.'</div>',
                        );
                    }
                return  $string;
            });

            $table->editColumn('typesIds', function ($row) {
                $ids = [];
                $types = $row->types;
                foreach ($types as $key => $value) {
                    $ids[$key] =  $value->id;
                }
                return  $ids;
            });

            $table->rawColumns(['actions', 'placeholder', 'types', 'typesIds']);

            return $table->make(true);
        }

        $paymentTypes = PaymentType::get();

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.paymentMethod.paymentmethods')." ".trans('global.listing') ]];
        return view('admin.paymentMethods.index',  compact('paymentTypes','breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('payment_method_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.paymentMethods.create');
    }

    public function store(Request $request)
    {
        try{
            $params = collect($request->all());
            $title = $params['title'];
            $types = $params['types'];
            $status = $params['status'];

            $paymentMethod = new PaymentMethod;
            $paymentMethod->title = $title;
            $paymentMethod->status = $status;
            $paymentMethod->save(); 

            $paymentMethod->types()->sync($types);

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PAYMENTMETHOD_ADDED_SUCCESSFULLY.code'),
                __('constants.messages.PAYMENTMETHOD_ADDED_SUCCESSFULLY.msg')
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

    public function edit(PaymentMethod $paymentMethod)
    {
        abort_if(Gate::denies('payment_method_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.paymentMethods.edit', compact('paymentMethod'));
    }

    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        try{
            $params = collect($request->all());
            $id = $paymentMethod->id;
            $types = $params['types'];

            $paymentMethod->update($request->all());

            //get all the payment types of current payment method
            $pivotIDs = MethodType::where('payment_method_id', $id)->pluck('payment_type_id')->toArray();

            $diffIdsToEdit = array_diff($pivotIDs, $types);
            if(!empty($diffIdsToEdit)){
                MethodType::where('payment_method_id', $id)->whereIn('payment_type_id', $diffIdsToEdit)->delete();
            }
            $diffIdsToAdd = array_diff($types, $pivotIDs);
            if(!empty($diffIdsToAdd)){
                 $paymentMethod->types()->attach($diffIdsToAdd);
            }

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PAYMENTMETHOD_UPDATE_SUCCESSFULLY.code'),
                __('constants.messages.PAYMENTMETHOD_UPDATE_SUCCESSFULLY.msg'),
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

    public function show(PaymentMethod $paymentMethod)
    {
        abort_if(Gate::denies('payment_method_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.paymentMethods.show', compact('paymentMethod'));
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        abort_if(Gate::denies('payment_method_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $paymentMethod->delete();

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PAYMENTMETHOD_DELETE_SUCCESSFULLY.code'),
                __('constants.messages.PAYMENTMETHOD_DELETE_SUCCESSFULLY.msg'),
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

    public function massDestroy(MassDestroyPaymentMethodRequest $request)
    {   
        try {
            PaymentMethod::whereIn('id', request('ids'))->delete();

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PAYMENTMETHOD_DELETE_SUCCESSFULLY.code'),
                __('constants.messages.PAYMENTMETHOD_DELETE_SUCCESSFULLY.msg'),
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
