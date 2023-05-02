<?php

namespace App\Http\Requests;

use App\Models\Weightmanage;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateWeightmanageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
        
        return Gate::allows('weightmanage_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
                'unique:weightmanages,title,' . request()->route('weightmanage')->id,
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
