<?php

namespace App\Http\Requests;

use App\Models\PaymentMethod;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePaymentTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
        return Gate::allows('payment_type_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:payment_types',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
