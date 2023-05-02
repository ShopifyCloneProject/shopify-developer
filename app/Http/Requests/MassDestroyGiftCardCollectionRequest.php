<?php

namespace App\Http\Requests;

use App\Models\GiftCardCollection;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyGiftCardCollectionRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('gift_card_collection_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:gift_card_collections,id',
        ];
    }
}
