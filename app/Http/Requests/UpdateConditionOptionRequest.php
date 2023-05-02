<?php

namespace App\Http\Requests;

use App\Models\ConditionOption;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateConditionOptionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('condition_option_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
            ],
        ];
    }
}
