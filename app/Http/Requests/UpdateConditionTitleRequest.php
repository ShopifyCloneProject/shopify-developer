<?php

namespace App\Http\Requests;

use App\Models\ConditionTitle;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateConditionTitleRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('condition_title_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
            ],
            'status' => [
                'required',
            ],
            'collection_condition' => [
                'string',
                'required',
            ],
        ];
    }
}
