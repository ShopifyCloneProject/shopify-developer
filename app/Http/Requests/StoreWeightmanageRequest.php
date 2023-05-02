<?php

namespace App\Http\Requests;

use App\Models\Weightmanage;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreWeightmanageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
        
        return Gate::allows('weightmanage_create');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
                'unique:weightmanages',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
