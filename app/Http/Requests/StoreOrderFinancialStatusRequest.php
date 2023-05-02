<?php

namespace App\Http\Requests;

use App\Models\OrderFinancialStatus;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreOrderFinancialStatusRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('order_financial_status_create');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
