<?php

namespace App\Http\Requests;

use App\Models\GiftCardCollection;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreGiftCardCollectionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('gift_card_collection_create');
    }

    public function rules()
    {
        return [];
    }
}
