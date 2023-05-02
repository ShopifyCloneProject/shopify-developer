<?php

namespace App\Http\Requests;

use App\Models\Section;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSectionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('section_create');
    }

    public function rules()
    {
        return [
            'columnname' => [
                'string',
                'required',
                'unique:sections',
            ],
            'displaycolumnname' => [
                'string',
                'required',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}