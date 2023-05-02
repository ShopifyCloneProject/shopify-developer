<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PaymentMethod;
use App\Models\PaymentType;
use App\Models\PaymentDetail;
use App\Models\MethodType;
use App\Models\PaymentMethodCustom;
use Gate;
use DB;
use Config;

class PaymentSettingsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('payment_settings_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    	$activatedPaymentMethods = PaymentMethod::whereHas('details', function ($query){
              $query->where('status', 1);
        })
        ->get();

        $manualPaymentMethodsOriginal = PaymentMethodCustom::where('type', '!=', 'custom')->get();
        $manualPaymentMethods = PaymentMethodCustom::where('status', 1)->get();

        $data['activatedPaymentMethods'] = $activatedPaymentMethods;
        $data['manualPaymentMethods'] = $manualPaymentMethods;
        $data['manualPaymentMethodsOriginal'] = $manualPaymentMethodsOriginal;
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')],['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')], ['name' => trans('cruds.paymentSettings.payment_provider')]];

    	return view('admin.settings.payment.index', compact('data','breadcrumbs'));
	}

    public function paymentMeethods()
    {
        abort_if(Gate::denies('payment_meethods_create') && Gate::denies('payment_meethods_access') , Response::HTTP_FORBIDDEN, '403 Forbidden');
        $paymentMethods = PaymentMethod::whereDoesntHave('details')
        ->orWhereHas('details', function ($query){
              $query->where('status', 0);
        })
        ->get();
        $paymentMethods->load('types');

        $paymentTypes = PaymentType::get();
       
        $data['paymentMethods'] = $paymentMethods;
        $data['paymentTypes'] = $paymentTypes;
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')],['link'=>route('admin.settings.payments'), 'name' => trans('cruds.paymentSettings.payment_provider')], ['name' => trans('cruds.paymentSettings.payment_methods')]];
        
        return view('admin.settings.payment.payment_methods', compact('data','breadcrumbs'));
    }

    public function paymentDetails($id)
    {
        abort_if(Gate::denies('payment_details_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $paymentMethods = PaymentMethod::where('id', $id)->first();
        $paymentMethods->load('types');
        $paymentMethods->load('details');

        $data['paymentMethods'] = $paymentMethods;
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')],['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')],['link'=>route('admin.settings.payments'), 'name' => trans('cruds.paymentSettings.payment_provider')], ['name' => trans('global.edit') .' '. trans('cruds.paymentSettings.payment_methods')]];
       
        return view('admin.settings.payment.payment_details', compact('data','breadcrumbs'));
    }

    public function activatePaymentMethod(Request $request){
          try{
            $params = collect($request->all());
            // $required = ['store_name'];
            // $this->validateRequiredParams($required,$params->keys()->toArray());
            
            $paymentMethodId = $params['id'];
            $userId = Config::get('client_id');
            $objPaymentDetails = PaymentDetail::where(['user_id' => $userId, 'payment_method_id' => $paymentMethodId])->first();
            if(empty($objPaymentDetails)){
                $objPaymentDetails = new PaymentDetail;
                $objPaymentDetails->user_id = $userId;
            } 

            $apiKey =  $params['apiKey'];
            $apiSecret =  $params['apiSecret'];
            $industryType =  $params['industryType'];
            $website =  $params['website'];
            $types =  $params['types'];
            $isTestMode =  $params['isTestMode'] ? 1 : 0;

            $objPaymentDetails->payment_method_id = $paymentMethodId;
            $objPaymentDetails->app_key = $apiKey;
            $objPaymentDetails->app_secret = $apiSecret;
            $objPaymentDetails->industry_type = $industryType;
            $objPaymentDetails->website = $website;
            $objPaymentDetails->is_testmode = $isTestMode;
            $objPaymentDetails->status = 1;
            $objPaymentDetails->save();

            MethodType::where('payment_method_id', $paymentMethodId)->update(['is_enabled' => 0]);
            MethodType::whereIn('payment_type_id', $types)->where('payment_method_id', $paymentMethodId)->update(['is_enabled' => 1]);

            $url = route('admin.settings.payments');
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PAYMENT_METHOD_ACTIVATE_SUCCESSFULLY.code'),
                __('constants.messages.PAYMENT_METHOD_ACTIVATE_SUCCESSFULLY.msg'),
                ['url'=>$url]
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

    public function deActivatePaymentMethod($id){
          try{
            
            $paymentDetails = PaymentDetail::where('id', $id)->update(['status' => 0]);

            $url = route('admin.settings.payments');
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PAYMENT_METHOD_DEACTIVATE_SUCCESSFULLY.code'),
                __('constants.messages.PAYMENT_METHOD_DEACTIVATE_SUCCESSFULLY.msg'),
                ['url'=>$url]
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

    public function createCustomPaymentMethod(Request $request){
          try{
            $params = collect($request->all());
            // $required = ['store_name'];
            // $this->validateRequiredParams($required,$params->keys()->toArray());
            
            // $paymentId = $params['id'];
            $userId = Config::get('client_id');
            // $paymentDetails = PaymentDetail::where('user_id', $userId)->where('user_id', $paymentId)->first();

            // if(!$paymentDetails){
            //     $paymentDetails = new PaymentDetail;
            //     $paymentDetails->user_id = $userId;
            // } 

            $paymentType =  $params['currentPaymentType'];
            $name =  $params['paymentMethodName'];
            $detail =  $params['additionalDetails'];
            $instructions =  $params['paymentInstructions'];

            if(isset($params['id'])){
                $paymentMethodCustom = PaymentMethodCustom::where('id', $params['id'])->first();
            } else {
                $paymentMethodCustom = new PaymentMethodCustom;
            }
            $paymentMethodCustom->user_id = $userId;
            $paymentMethodCustom->type = $paymentType;
            $paymentMethodCustom->name = $name;
            $paymentMethodCustom->additional_details = $detail;
            $paymentMethodCustom->additional_instruction = $instructions;
            $paymentMethodCustom->status = 1;
            $paymentMethodCustom->save();

            if(isset($params['id'])){
                $manualPaymentMethods = PaymentMethodCustom::where('status', 1)->get();
                $manualPaymentMethodsOriginal = PaymentMethodCustom::where('type', '!=', 'custom')->get();
                $data['manualPaymentMethods'] = $manualPaymentMethods;
                $data['manualPaymentMethodsOriginal'] = $manualPaymentMethodsOriginal;
                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.DATA_SAVED_SUCCESSFULLY.code'),
                    __('constants.messages.DATA_SAVED_SUCCESSFULLY.msg'),
                    $data
                );
            } else {
                $manualPaymentMethods = $paymentMethodCustom;
                $manualPaymentMethodsOriginal = PaymentMethodCustom::where('type', '!=', 'custom')->get();
                $data['manualPaymentMethods'] = $manualPaymentMethods;
                $data['manualPaymentMethodsOriginal'] = $manualPaymentMethodsOriginal;

                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.PAYMENT_METHOD_ACTIVATE_SUCCESSFULLY.code'),
                    __('constants.messages.PAYMENT_METHOD_ACTIVATE_SUCCESSFULLY.msg'),
                    $data
                );
            }
        } catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function deActivateCustomPaymentMethod($id){
          try{
            
            $paymentDetails = PaymentMethodCustom::where('id', $id)->update(['status' => 0]);
            
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PAYMENT_METHOD_DEACTIVATE_SUCCESSFULLY.code'),
                __('constants.messages.PAYMENT_METHOD_DEACTIVATE_SUCCESSFULLY.msg')
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
