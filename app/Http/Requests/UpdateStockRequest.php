<?php

namespace App\Http\Requests;

use App\Models\Stock;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateStockRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('stock_edit');
    }

    public function rules()
    {
        return [
            'quantity' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'available_quantity' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'defect_quantity' => [
                'string',
                'nullable',
            ],
            'product_id' => [
                'required',
                'integer',
            ],
            'address_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
