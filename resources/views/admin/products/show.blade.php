@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.product.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.products.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.id') }}
                        </th>
                        <td>
                            {{ $product->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.title') }}
                        </th>
                        <td>
                            {{ $product->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.description') }}
                        </th>
                        <td>
                            {!! $product->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.product_type') }}
                        </th>
                        <td>
                            {{ $product->product_type->title ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.vendor') }}
                        </th>
                        <td>
                            {{ $product->vendor->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Product::STATUS_SELECT[$product->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.price') }}
                        </th>
                        <td>
                            {{ $product->price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.compare_at_price') }}
                        </th>
                        <td>
                            {{ $product->compare_at_price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.cost_per_item') }}
                        </th>
                        <td>
                            {{ $product->cost_per_item }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.is_product_charge') }}
                        </th>
                        <td>
                            {{ App\Models\Product::IS_PRODUCT_CHARGE_RADIO[$product->is_product_charge] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.sku') }}
                        </th>
                        <td>
                            {{ $product->sku }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.barcode') }}
                        </th>
                        <td>
                            {{ $product->barcode }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.is_track') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $product->is_track ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.is_continue_selling') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $product->is_continue_selling ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.is_pysical_product') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $product->is_pysical_product ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.weight') }}
                        </th>
                        <td>
                            {{ $product->weight }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.weight_type') }}
                        </th>
                        <td>
                            {{ $product->weight_type->title ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.country') }}
                        </th>
                        <td>
                            {{ $product->country->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.hs_code') }}
                        </th>
                        <td>
                            {{ $product->hs_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.min_order_limit') }}
                        </th>
                        <td>
                            {{ $product->min_order_limit }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.max_order_limit') }}
                        </th>
                        <td>
                            {{ $product->max_order_limit }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.is_cod_enabled') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $product->is_cod_enabled ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.is_size_chart_ebabled') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $product->is_size_chart_ebabled ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.is_special_product') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $product->is_special_product ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.special_price') }}
                        </th>
                        <td>
                            {{ $product->special_price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.expiry_date') }}
                        </th>
                        <td>
                            {{ $product->expiry_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.special_product_status') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $product->special_product_status ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.seo_title') }}
                        </th>
                        <td>
                            {{ $product->seo_title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.seo_description') }}
                        </th>
                        <td>
                            {{ $product->seo_description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.is_gift_card') }}
                        </th>
                        <td>
                            {{ App\Models\Product::IS_GIFT_CARD_RADIO[$product->is_gift_card] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.column') }}
                        </th>
                        <td>
                            {{ $product->column }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.products.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection