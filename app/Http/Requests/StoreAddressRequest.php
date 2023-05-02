<?php

namespace App\Http\Requests;

use App\Models\Address;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAddressRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('address_create');
    }

    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'integer',
            ],
            'address' => [
                'string',
                'required',
                'min:10',
                'max:80'
            ],
            'address_2' => [
                'string',
                'nullable',
            ],
            'phone_code' => [
                'string',
                'required',
            ],
            'mobile' => [
                'integer',
                'nullable',
                'digits:10',
                'required'
            ],
            'postal_code' => [
                'string',
                'required',
            ],
            'city_name' => [
                'string',
                'required',
            ],
            'state_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
