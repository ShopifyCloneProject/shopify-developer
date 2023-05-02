<?php

namespace App\Http\Requests;

use App\Models\GiftCardDenomination;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateGiftCardDenominationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('gift_card_denomination_edit');
    }

    public function rules()
    {
        return [
            'value' => [
                'string',
                'required',
            ],
        ];
    }
}
