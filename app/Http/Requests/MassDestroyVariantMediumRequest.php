<?php

namespace App\Http\Requests;

use App\Models\VariantMedium;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyVariantMediumRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('variant_medium_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:variant_media,id',
        ];
    }
}
