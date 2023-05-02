<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\UserDetail;
use Illuminate\Http\Response;

class UpdateAccountInformationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bio' => [
                'string',
            ],
            'birth_date' => [
                'date_format:Y-m-d',
            ], 
            'country_id' => [
                'string',
            ],
            'phone' => [
                'regex:/[0-9]/',
                'min:10'
            ],
        ];
    }
}
