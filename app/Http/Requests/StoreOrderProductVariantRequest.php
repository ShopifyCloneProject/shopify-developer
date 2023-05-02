<?php

namespace App\Http\Requests;

use App\Models\OrderProductVariant;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreOrderProductVariantRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('order_product_variant_create');
    }

    public function rules()
    {
        return [];
    }
}
