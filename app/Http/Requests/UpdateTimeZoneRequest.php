<?php

namespace App\Http\Requests;

use App\Models\TimeZone;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTimeZoneRequest extends FormRequest
{
    public function authorize()
    {
        return true;
        return Gate::allows('time_zone_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
            ],
            'timezone_value' => [
                'string',
                'required',
            ],
        ];
    }
}
