<?php

namespace App\Http\Requests;

use App\Models\GiftCardIssue;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreGiftCardIssueRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('gift_card_issue_create');
        return true;
    }

    public function rules()
    {
        return [
            'code' => [
                'string',
                'required',
            ],
            'date_issued' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'expiration_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'disabled_at' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
        ];
    }
}
