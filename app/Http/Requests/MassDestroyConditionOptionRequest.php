<?php

namespace App\Http\Requests;

use App\Models\ConditionOption;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyConditionOptionRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('condition_option_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:condition_options,id',
        ];
    }
}
