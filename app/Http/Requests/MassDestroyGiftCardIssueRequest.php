<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Models\GiftCardIssue;
use Gate;

class MassDestroyGiftCardIssueRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('gift_card_issue_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:gift_card_issues,id',
        ];
    }
}
