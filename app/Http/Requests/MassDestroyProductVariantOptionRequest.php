<?php

namespace App\Http\Requests;

use App\Models\ProductVariantOption;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyProductVariantOptionRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('product_variant_option_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:product_variant_options,id',
        ];
    }
}
