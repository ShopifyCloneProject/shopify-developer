<?php

namespace App\Http\Requests;

use App\Models\GiftCardVendor;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreGiftCardVendorRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('gift_card_vendor_create');
    }

    public function rules()
    {
        return [];
    }
}
