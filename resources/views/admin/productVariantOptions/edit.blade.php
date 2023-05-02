@extends('layouts/contentLayoutMaster')

@section('vendor-style')
<!-- vendor css files -->
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.productVariantOption.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.product-variant-options.update", [$productVariantOption->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="product_id">{{ trans('cruds.productVariantOption.fields.product') }}</label>
                <select class="form-control select2 {{ $errors->has('product') ? 'is-invalid' : '' }}" name="product_id" id="product_id">
                    @foreach($products as $id => $entry)
                        <option value="{{ $id }}" {{ (old('product_id') ? old('product_id') : $productVariantOption->product->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('product'))
                    <span class="text-danger">{{ $errors->first('product') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.product_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="variant_option_1_id">{{ trans('cruds.productVariantOption.fields.variant_option_1') }}</label>
                <select class="form-control select2 {{ $errors->has('variant_option_1') ? 'is-invalid' : '' }}" name="variant_option_1_id" id="variant_option_1_id">
                    @foreach($variant_option_1s as $id => $entry)
                        <option value="{{ $id }}" {{ (old('variant_option_1_id') ? old('variant_option_1_id') : $productVariantOption->variant_option_1->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('variant_option_1'))
                    <span class="text-danger">{{ $errors->first('variant_option_1') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.variant_option_1_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="variant_option_2_id">{{ trans('cruds.productVariantOption.fields.variant_option_2') }}</label>
                <select class="form-control select2 {{ $errors->has('variant_option_2') ? 'is-invalid' : '' }}" name="variant_option_2_id" id="variant_option_2_id">
                    @foreach($variant_option_2s as $id => $entry)
                        <option value="{{ $id }}" {{ (old('variant_option_2_id') ? old('variant_option_2_id') : $productVariantOption->variant_option_2->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('variant_option_2'))
                    <span class="text-danger">{{ $errors->first('variant_option_2') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.variant_option_2_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="variant_option_3_id">{{ trans('cruds.productVariantOption.fields.variant_option_3') }}</label>
                <select class="form-control select2 {{ $errors->has('variant_option_3') ? 'is-invalid' : '' }}" name="variant_option_3_id" id="variant_option_3_id">
                    @foreach($variant_option_3s as $id => $entry)
                        <option value="{{ $id }}" {{ (old('variant_option_3_id') ? old('variant_option_3_id') : $productVariantOption->variant_option_3->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('variant_option_3'))
                    <span class="text-danger">{{ $errors->first('variant_option_3') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.variant_option_3_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="src">{{ trans('cruds.productVariantOption.fields.src') }}</label>
                <input class="form-control {{ $errors->has('src') ? 'is-invalid' : '' }}" type="text" name="src" id="src" value="{{ old('src', $productVariantOption->src) }}">
                @if($errors->has('src'))
                    <span class="text-danger">{{ $errors->first('src') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.src_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="src_alt_text">{{ trans('cruds.productVariantOption.fields.src_alt_text') }}</label>
                <input class="form-control {{ $errors->has('src_alt_text') ? 'is-invalid' : '' }}" type="text" name="src_alt_text" id="src_alt_text" value="{{ old('src_alt_text', $productVariantOption->src_alt_text) }}">
                @if($errors->has('src_alt_text'))
                    <span class="text-danger">{{ $errors->first('src_alt_text') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.src_alt_text_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="price">{{ trans('cruds.productVariantOption.fields.price') }}</label>
                <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="number" name="price" id="price" value="{{ old('price', $productVariantOption->price) }}" step="0.01">
                @if($errors->has('price'))
                    <span class="text-danger">{{ $errors->first('price') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.price_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="compare_at_price">{{ trans('cruds.productVariantOption.fields.compare_at_price') }}</label>
                <input class="form-control {{ $errors->has('compare_at_price') ? 'is-invalid' : '' }}" type="number" name="compare_at_price" id="compare_at_price" value="{{ old('compare_at_price', $productVariantOption->compare_at_price) }}" step="0.01">
                @if($errors->has('compare_at_price'))
                    <span class="text-danger">{{ $errors->first('compare_at_price') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.compare_at_price_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="cost_per_item">{{ trans('cruds.productVariantOption.fields.cost_per_item') }}</label>
                <input class="form-control {{ $errors->has('cost_per_item') ? 'is-invalid' : '' }}" type="number" name="cost_per_item" id="cost_per_item" value="{{ old('cost_per_item', $productVariantOption->cost_per_item) }}" step="0.01">
                @if($errors->has('cost_per_item'))
                    <span class="text-danger">{{ $errors->first('cost_per_item') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.cost_per_item_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.productVariantOption.fields.is_product_charge') }}</label>
                @foreach(App\Models\ProductVariantOption::IS_PRODUCT_CHARGE_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('is_product_charge') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="is_product_charge_{{ $key }}" name="is_product_charge" value="{{ $key }}" {{ old('is_product_charge', $productVariantOption->is_product_charge) ===  $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_product_charge_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('is_product_charge'))
                    <span class="text-danger">{{ $errors->first('is_product_charge') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.is_product_charge_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="sku">{{ trans('cruds.productVariantOption.fields.sku') }}</label>
                <input class="form-control {{ $errors->has('sku') ? 'is-invalid' : '' }}" type="text" name="sku" id="sku" value="{{ old('sku', $productVariantOption->sku) }}" required>
                @if($errors->has('sku'))
                    <span class="text-danger">{{ $errors->first('sku') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.sku_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="barcode">{{ trans('cruds.productVariantOption.fields.barcode') }}</label>
                <input class="form-control {{ $errors->has('barcode') ? 'is-invalid' : '' }}" type="text" name="barcode" id="barcode" value="{{ old('barcode', $productVariantOption->barcode) }}">
                @if($errors->has('barcode'))
                    <span class="text-danger">{{ $errors->first('barcode') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.barcode_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_track') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_track" value="0">
                    <input class="form-check-input" type="checkbox" name="is_track" id="is_track" value="1" {{ $productVariantOption->is_track || old('is_track', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_track">{{ trans('cruds.productVariantOption.fields.is_track') }}</label>
                </div>
                @if($errors->has('is_track'))
                    <span class="text-danger">{{ $errors->first('is_track') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.is_track_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_continue_selling') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_continue_selling" value="0">
                    <input class="form-check-input" type="checkbox" name="is_continue_selling" id="is_continue_selling" value="1" {{ $productVariantOption->is_continue_selling || old('is_continue_selling', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_continue_selling">{{ trans('cruds.productVariantOption.fields.is_continue_selling') }}</label>
                </div>
                @if($errors->has('is_continue_selling'))
                    <span class="text-danger">{{ $errors->first('is_continue_selling') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.is_continue_selling_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_physical_product') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_physical_product" value="0">
                    <input class="form-check-input" type="checkbox" name="is_physical_product" id="is_physical_product" value="1" {{ $productVariantOption->is_physical_product || old('is_physical_product', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_physical_product">{{ trans('cruds.productVariantOption.fields.is_physical_product') }}</label>
                </div>
                @if($errors->has('is_physical_product'))
                    <span class="text-danger">{{ $errors->first('is_physical_product') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.is_physical_product_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="weight">{{ trans('cruds.productVariantOption.fields.weight') }}</label>
                <input class="form-control {{ $errors->has('weight') ? 'is-invalid' : '' }}" type="number" name="weight" id="weight" value="{{ old('weight', $productVariantOption->weight) }}" step="0.01">
                @if($errors->has('weight'))
                    <span class="text-danger">{{ $errors->first('weight') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.weight_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="weight_type_id">{{ trans('cruds.productVariantOption.fields.weight_type') }}</label>
                <select class="form-control select2 {{ $errors->has('weight_type') ? 'is-invalid' : '' }}" name="weight_type_id" id="weight_type_id">
                    @foreach($weight_types as $id => $entry)
                        <option value="{{ $id }}" {{ (old('weight_type_id') ? old('weight_type_id') : $productVariantOption->weight_type->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('weight_type'))
                    <span class="text-danger">{{ $errors->first('weight_type') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.weight_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="country_id">{{ trans('cruds.productVariantOption.fields.country') }}</label>
                <select class="form-control select2 {{ $errors->has('country') ? 'is-invalid' : '' }}" name="country_id" id="country_id">
                    @foreach($countries as $id => $entry)
                        <option value="{{ $id }}" {{ (old('country_id') ? old('country_id') : $productVariantOption->country->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('country'))
                    <span class="text-danger">{{ $errors->first('country') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.country_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="hs_code">{{ trans('cruds.productVariantOption.fields.hs_code') }}</label>
                <input class="form-control {{ $errors->has('hs_code') ? 'is-invalid' : '' }}" type="text" name="hs_code" id="hs_code" value="{{ old('hs_code', $productVariantOption->hs_code) }}">
                @if($errors->has('hs_code'))
                    <span class="text-danger">{{ $errors->first('hs_code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.hs_code_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_special_product') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_special_product" value="0">
                    <input class="form-check-input" type="checkbox" name="is_special_product" id="is_special_product" value="1" {{ $productVariantOption->is_special_product || old('is_special_product', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_special_product">{{ trans('cruds.productVariantOption.fields.is_special_product') }}</label>
                </div>
                @if($errors->has('is_special_product'))
                    <span class="text-danger">{{ $errors->first('is_special_product') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.is_special_product_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="special_price">{{ trans('cruds.productVariantOption.fields.special_price') }}</label>
                <input class="form-control {{ $errors->has('special_price') ? 'is-invalid' : '' }}" type="number" name="special_price" id="special_price" value="{{ old('special_price', $productVariantOption->special_price) }}" step="0.01">
                @if($errors->has('special_price'))
                    <span class="text-danger">{{ $errors->first('special_price') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.special_price_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="expiry_date">{{ trans('cruds.productVariantOption.fields.expiry_date') }}</label>
                <input class="form-control date flatpickr-basic {{ $errors->has('expiry_date') ? 'is-invalid' : '' }}" type="text" name="expiry_date" id="expiry_date" value="{{ old('expiry_date', $productVariantOption->expiry_date) }}">
                @if($errors->has('expiry_date'))
                    <span class="text-danger">{{ $errors->first('expiry_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.expiry_date_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('special_product_status') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="special_product_status" value="0">
                    <input class="form-check-input" type="checkbox" name="special_product_status" id="special_product_status" value="1" {{ $productVariantOption->special_product_status || old('special_product_status', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="special_product_status">{{ trans('cruds.productVariantOption.fields.special_product_status') }}</label>
                </div>
                @if($errors->has('special_product_status'))
                    <span class="text-danger">{{ $errors->first('special_product_status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.special_product_status_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_shipping') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_shipping" value="0">
                    <input class="form-check-input" type="checkbox" name="is_shipping" id="is_shipping" value="1" {{ $productVariantOption->is_shipping || old('is_shipping', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_shipping">{{ trans('cruds.productVariantOption.fields.is_shipping') }}</label>
                </div>
                @if($errors->has('is_shipping'))
                    <span class="text-danger">{{ $errors->first('is_shipping') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.is_shipping_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_taxable') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_taxable" value="0">
                    <input class="form-check-input" type="checkbox" name="is_taxable" id="is_taxable" value="1" {{ $productVariantOption->is_taxable || old('is_taxable', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_taxable">{{ trans('cruds.productVariantOption.fields.is_taxable') }}</label>
                </div>
                @if($errors->has('is_taxable'))
                    <span class="text-danger">{{ $errors->first('is_taxable') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.is_taxable_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="reorder">{{ trans('cruds.productVariantOption.fields.reorder') }}</label>
                <input class="form-control {{ $errors->has('reorder') ? 'is-invalid' : '' }}" type="number" name="reorder" id="reorder" value="{{ old('reorder', $productVariantOption->reorder) }}" step="1" required>
                @if($errors->has('reorder'))
                    <span class="text-danger">{{ $errors->first('reorder') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productVariantOption.fields.reorder_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection

@section('vendor-script')
<!-- vendor files -->
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection
@section('page-script')
<!-- Page js files -->
<script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
@endsection