<?php

namespace App\Http\Requests;

use App\Models\GiftCardTag;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateGiftCardTagRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('gift_card_tag_edit');
    }

    public function rules()
    {
        return [];
    }
}
