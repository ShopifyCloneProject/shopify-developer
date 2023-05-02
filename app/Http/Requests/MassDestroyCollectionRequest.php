<?php

namespace App\Http\Requests;

use App\Models\Collection;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyCollectionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
        abort_if(Gate::denies('collection_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:collections,id',
        ];
    }
}
