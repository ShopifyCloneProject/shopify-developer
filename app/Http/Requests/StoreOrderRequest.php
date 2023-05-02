<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use App\Models\Order;
use Gate;

class StoreOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
        return Gate::allows('order_create');
    }

    public function rules()
    {
        return [
            'paid_at' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'fulfillment_status' => [
                'required',
            ],
            'fulfilled_at' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'discount_code' => [
                'string',
                'nullable',
            ],
            'receipt_number' => [
                'string',
                'nullable',
            ],
            'tax_1_name' => [
                'string',
                'nullable',
            ],
            'tax_2_name' => [
                'string',
                'nullable',
            ],
            'tax_3_name' => [
                'string',
                'nullable',
            ],
            'tax_4_name' => [
                'string',
                'nullable',
            ],
            'tax_5_name' => [
                'string',
                'nullable',
            ],
        ];
    }
}
