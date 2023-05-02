<?php

namespace App\Http\Requests;

use App\Models\OrderProductVariant;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateOrderProductVariantRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('order_product_variant_edit');
    }

    public function rules()
    {
        return [];
    }
}
