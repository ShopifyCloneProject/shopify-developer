<?php

namespace App\Http\Requests;

use App\Models\Fbpixel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class MassDestroyFbpixelRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('fbpixel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:fbpixels,id',
        ];
    }
}
