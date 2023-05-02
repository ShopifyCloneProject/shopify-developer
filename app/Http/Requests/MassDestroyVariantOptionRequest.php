<?php

namespace App\Http\Requests;

use App\Models\VariantOption;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyVariantOptionRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('variant_option_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:variant_options,id',
        ];
    }
}
