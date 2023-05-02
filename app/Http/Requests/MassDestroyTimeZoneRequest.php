<?php

namespace App\Http\Requests;

use App\Models\TimeZone;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTimeZoneRequest extends FormRequest
{
    public function authorize()
    {
        return true;
        
        abort_if(Gate::denies('time_zone_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:time_zones,id',
        ];
    }
}
