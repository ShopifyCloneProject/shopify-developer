<?php

namespace App\Http\Requests;

use App\Models\Stock;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreStockRequest extends FormRequest
{
    public function authorize()
    {
        // dd(FormRequest::all());
        return Gate::allows('stock_create');
        return true;
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
                'string',
            ],
            'address_id' => [
                'required',
                'string',
            ],
        ];
    }
}
