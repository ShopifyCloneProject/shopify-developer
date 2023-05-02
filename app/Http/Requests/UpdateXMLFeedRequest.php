<?php

namespace App\Http\Requests;

use App\Models\XMLS;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateXMLFeedRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('xmlfeed_edit');
        return true;
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
            ],
        ];
    }
}
