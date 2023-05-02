<?php

namespace App\Http\Requests;

use App\Models\UserStoreIndustry;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateUserStoreIndustryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('user_store_industry_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
