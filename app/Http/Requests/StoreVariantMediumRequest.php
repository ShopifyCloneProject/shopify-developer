<?php

namespace App\Http\Requests;

use App\Models\VariantMedium;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreVariantMediumRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('variant_medium_create');
    }

    public function rules()
    {
        return [
            'src' => [
                'string',
                'nullable',
            ],
            'src_alt_text' => [
                'string',
                'nullable',
            ],
            'reorder' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
