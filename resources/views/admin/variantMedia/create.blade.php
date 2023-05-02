@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.variantMedium.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.variant-media.store") }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="convert" value="1">
            <div class="form-group">
                <label for="product_variant_id">{{ trans('cruds.variantMedium.fields.product_variant') }}</label>
                <select class="form-control select2 {{ $errors->has('product_variant') ? 'is-invalid' : '' }}" name="product_variant_id" id="product_variant_id">
                    @foreach($product_variants as $id => $entry)
                        <option value="{{ $id }}" {{ old('product_variant_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('product_variant'))
                    <span class="text-danger">{{ $errors->first('product_variant') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.variantMedium.fields.product_variant_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="product_id">{{ trans('cruds.variantMedium.fields.product') }}</label>
                <select class="form-control select2 {{ $errors->has('product') ? 'is-invalid' : '' }}" name="product_id" id="product_id">
                    @foreach($products as $id => $entry)
                        <option value="{{ $id }}" {{ old('product_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('product'))
                    <span class="text-danger">{{ $errors->first('product') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.variantMedium.fields.product_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="src">{{ trans('cruds.variantMedium.fields.src') }}</label>
                <input class="form-control {{ $errors->has('src') ? 'is-invalid' : '' }}" type="text" name="src" id="src" value="{{ old('src', '') }}">
                @if($errors->has('src'))
                    <span class="text-danger">{{ $errors->first('src') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.variantMedium.fields.src_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="src_alt_text">{{ trans('cruds.variantMedium.fields.src_alt_text') }}</label>
                <input class="form-control {{ $errors->has('src_alt_text') ? 'is-invalid' : '' }}" type="text" name="src_alt_text" id="src_alt_text" value="{{ old('src_alt_text', '') }}">
                @if($errors->has('src_alt_text'))
                    <span class="text-danger">{{ $errors->first('src_alt_text') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.variantMedium.fields.src_alt_text_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_default') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_default" value="0">
                    <input class="form-check-input" type="checkbox" name="is_default" id="is_default" value="1" {{ old('is_default', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_default">{{ trans('cruds.variantMedium.fields.is_default') }}</label>
                </div>
                @if($errors->has('is_default'))
                    <span class="text-danger">{{ $errors->first('is_default') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.variantMedium.fields.is_default_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="reorder">{{ trans('cruds.variantMedium.fields.reorder') }}</label>
                <input class="form-control {{ $errors->has('reorder') ? 'is-invalid' : '' }}" type="number" name="reorder" id="reorder" value="{{ old('reorder', '') }}" step="1">
                @if($errors->has('reorder'))
                    <span class="text-danger">{{ $errors->first('reorder') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.variantMedium.fields.reorder_helper') }}</span>
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