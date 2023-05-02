<?php

namespace App\Http\Requests;

use App\Models\UserStore;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateUserStoreRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('user_store_edit');
    }

    public function rules()
    {
        return [
            'store_contact_email' => [
                'required',
            ],
            'sender_email' => [
                'string',
                'required',
            ],
            'company' => [
                'string',
                'nullable',
            ],
            'unit_system' => [
                'required',
            ],
            'unit_weight' => [
                'required',
            ],
            'prefix' => [
                'string',
                'nullable',
            ],
            'suffix' => [
                'string',
                'nullable',
            ],
        ];
    }
}
