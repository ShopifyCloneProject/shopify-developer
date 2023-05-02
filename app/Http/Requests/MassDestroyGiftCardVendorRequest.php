<?php

namespace App\Http\Requests;

use App\Models\GiftCardVendor;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyGiftCardVendorRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('gift_card_vendor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:gift_card_vendors,id',
        ];
    }
}
