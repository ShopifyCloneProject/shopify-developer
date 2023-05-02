<?php

namespace App\Http\Requests;

use App\Models\Dimension;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreDimensionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        return Gate::allows('dimensions_create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
                'unique:dimensions',
            ],
            'short_code' => [
                'string',
                'nullable'
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
