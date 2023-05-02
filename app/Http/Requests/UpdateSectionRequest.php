<?php

namespace App\Http\Requests;

use App\Models\Section;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSectionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('section_edit');
    }

    public function rules()
    {
        return [
            'columnname' => [
                'string',
                'required',
                'unique:sections,columnname,' . request()->route('section')->id,
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