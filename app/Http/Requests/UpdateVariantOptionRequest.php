<?php

namespace App\Http\Requests;

use App\Models\VariantOption;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateVariantOptionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('variant_option_edit');
    }

    public function rules()
    {
        return [
            'options' => [
                'string',
                'required',
            ],
            'status' => [
                'required',
            ],
            'variant_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
