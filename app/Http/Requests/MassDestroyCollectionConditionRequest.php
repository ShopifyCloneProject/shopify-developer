<?php

namespace App\Http\Requests;

use App\Models\CollectionCondition;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyCollectionConditionRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('collection_condition_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:collection_conditions,id',
        ];
    }
}
