<?php

namespace App\Http\Requests;

use App\Models\ProductVariantOption;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateProductVariantOptionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_variant_option_edit');
    }

    public function rules()
    {
        return [
            'src' => [
                'string',
                'nullable',
            ],
            'src_alt_text' => [
                'string',
                'nullable',
            ],
            'sku' => [
                'string',
                'required',
                'unique:product_variant_options,sku,' . request()->route('product_variant_option')->id,
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
            'expiry_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'reorder' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
