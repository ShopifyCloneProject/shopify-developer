<?php

namespace App\Http\Requests;

use App\Models\Fbpixel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreFbpixelRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('fbpixel_create');
    }

    public function rules()
    {
        return [
            'pixel' => [
                'string',
                'required',
                'unique:fbpixels',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
