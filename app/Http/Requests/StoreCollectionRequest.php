<?php

namespace App\Http\Requests;

use App\Models\Collection;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCollectionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('collection_create');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
            ],
            'collection_type' => [
                'required',
            ],
            'description_position' => [
                'required',
            ],
            'seo_keywords' => [
                'string',
                'nullable',
            ],
            'status' => [
                'required',
            ],
            'src_alt_text' => [
                'string',
                'nullable',
            ],
            'url' => [
                'string',
                'nullable',
            ],
            'schedule_time' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
        ];
    }
}
