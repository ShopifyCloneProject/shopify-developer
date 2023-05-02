<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use App\Models\Notification;
use Gate;

class StoreNotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('notification_create');
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
            'title' => [
                'string',
                'required',
            ],
            'category' => [
                'integer',
                'required',
            ],
            'options' => [
                'integer',
                'required',
            ],
            'status' => [
                'string',
                'required',
            ],
        ];
    }
}
