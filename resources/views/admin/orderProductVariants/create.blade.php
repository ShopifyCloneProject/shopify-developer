@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.orderProductVariant.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.order-product-variants.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="order_detail_id">{{ trans('cruds.orderProductVariant.fields.order_detail') }}</label>
                <select class="form-control select2 {{ $errors->has('order_detail') ? 'is-invalid' : '' }}" name="order_detail_id" id="order_detail_id">
                    @foreach($order_details as $id => $entry)
                        <option value="{{ $id }}" {{ old('order_detail_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('order_detail'))
                    <span class="text-danger">{{ $errors->first('order_detail') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.orderProductVariant.fields.order_detail_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="product_variant_id">{{ trans('cruds.orderProductVariant.fields.product_variant') }}</label>
                <select class="form-control select2 {{ $errors->has('product_variant') ? 'is-invalid' : '' }}" name="product_variant_id" id="product_variant_id">
                    @foreach($product_variants as $id => $entry)
                        <option value="{{ $id }}" {{ old('product_variant_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('product_variant'))
                    <span class="text-danger">{{ $errors->first('product_variant') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.orderProductVariant.fields.product_variant_helper') }}</span>
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