<?php

namespace App\Http\Requests;

use App\Models\User;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('user_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required'
            ],
            'last_name' => [
                'string',
                'nullable'
            ],
            'mobile' => [
                'string',
                'nullable',
                'unique:users,mobile,'.$this->id
            ],
            'email' => [
                'required',
                'unique:users,email,'.$this->id
            ],
            'google' => [
                'string',
                'nullable'
            ],
            'facebook' => [
                'string',
                'nullable'
            ],
            'company' => [
                'string',
                'nullable'
            ],
            'total_orders' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
