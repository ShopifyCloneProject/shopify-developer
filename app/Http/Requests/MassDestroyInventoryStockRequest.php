<?php

namespace App\Http\Requests;

use App\Models\InventoryStock;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyInventoryStockRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('inventory_stock_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:inventory_stocks,id',
        ];
    }
}
