<?php

namespace App\Http\Requests;

use App\Models\Fbpixel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateFbpixelRequest extends FormRequest
{
     public function authorize()
    {
        return Gate::allows('fbpixel_edit');
    }

    public function rules()
    {
        return [
            'pixel' => [
                'string',
                'required',
                'unique:fbpixels,pixel,' . request()->route('fbpixel')->id,
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
