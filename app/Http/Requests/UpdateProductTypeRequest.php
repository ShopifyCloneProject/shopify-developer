<?php

namespace App\Http\Requests;

use App\Models\ProductType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateProductTypeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_type_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
                'unique:product_types,title,' . request()->route('product_type')->id,
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
