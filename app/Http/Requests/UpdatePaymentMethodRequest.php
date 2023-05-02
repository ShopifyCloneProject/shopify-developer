<?php

namespace App\Http\Requests;

use App\Models\PaymentMethod;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePaymentMethodRequest extends FormRequest
{
    public function authorize()
    {
        return true;
        
        return Gate::allows('payment_method_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
                'unique:payment_methods,title,' . request()->route('payment_method')->id,
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
