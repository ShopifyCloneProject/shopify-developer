@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.productVariantOption.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.product-variant-options.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.id') }}
                        </th>
                        <td>
                            {{ $productVariantOption->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.product') }}
                        </th>
                        <td>
                            {{ $productVariantOption->product->title ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.variant_option_1') }}
                        </th>
                        <td>
                            {{ $productVariantOption->variant_option_1->options ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.variant_option_2') }}
                        </th>
                        <td>
                            {{ $productVariantOption->variant_option_2->options ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.variant_option_3') }}
                        </th>
                        <td>
                            {{ $productVariantOption->variant_option_3->options ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.src') }}
                        </th>
                        <td>
                            {{ $productVariantOption->src }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.src_alt_text') }}
                        </th>
                        <td>
                            {{ $productVariantOption->src_alt_text }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.price') }}
                        </th>
                        <td>
                            {{ $productVariantOption->price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.compare_at_price') }}
                        </th>
                        <td>
                            {{ $productVariantOption->compare_at_price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.cost_per_item') }}
                        </th>
                        <td>
                            {{ $productVariantOption->cost_per_item }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.is_product_charge') }}
                        </th>
                        <td>
                            {{ App\Models\ProductVariantOption::IS_PRODUCT_CHARGE_RADIO[$productVariantOption->is_product_charge] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.sku') }}
                        </th>
                        <td>
                            {{ $productVariantOption->sku }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.barcode') }}
                        </th>
                        <td>
                            {{ $productVariantOption->barcode }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.is_track') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $productVariantOption->is_track ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.is_continue_selling') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $productVariantOption->is_continue_selling ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.is_physical_product') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $productVariantOption->is_physical_product ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.weight') }}
                        </th>
                        <td>
                            {{ $productVariantOption->weight }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.weight_type') }}
                        </th>
                        <td>
                            {{ $productVariantOption->weight_type->title ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.country') }}
                        </th>
                        <td>
                            {{ $productVariantOption->country->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.hs_code') }}
                        </th>
                        <td>
                            {{ $productVariantOption->hs_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.is_special_product') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $productVariantOption->is_special_product ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.special_price') }}
                        </th>
                        <td>
                            {{ $productVariantOption->special_price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.expiry_date') }}
                        </th>
                        <td>
                            {{ $productVariantOption->expiry_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.special_product_status') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $productVariantOption->special_product_status ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.is_shipping') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $productVariantOption->is_shipping ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.is_taxable') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $productVariantOption->is_taxable ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariantOption.fields.reorder') }}
                        </th>
                        <td>
                            {{ $productVariantOption->reorder }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-primary mt-2" href="{{ route('admin.product-variant-options.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection