<?php

namespace App\Http\Requests;

use App\Models\CollectionCondition;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCollectionConditionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('collection_condition_edit');
    }

    public function rules()
    {
        return [
            'model_name' => [
                'string',
                'nullable',
            ],
            'model_ref' => [
                'string',
                'nullable',
            ],
            'value' => [
                'string',
                'nullable',
            ],
            'collection_title_id' => [
                'required',
                'integer',
            ],
            'condition_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
