<?php

namespace App\Http\Requests;

use App\Models\Product;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'nullable',
            ],
            'status' => [
                'required',
            ],
            'is_product_charge' => [
                'required',
            ],
            'sku' => [
                'string',
                'nullable',
            ],
            'barcode' => [
                'string',
                'nullable',
            ],
            'weight' => [
                'numeric',
            ],
            'hs_code' => [
                'string',
                'nullable',
            ],
            'min_order_limit' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'max_order_limit' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'expiry_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'seo_title' => [
                'string',
                'nullable',
            ],
            'seo_description' => [
                'string',
                'nullable',
            ],
            'is_gift_card' => [
                'required',
            ],
            'column' => [
                'string',
                'nullable',
            ],
        ];
    }
}
