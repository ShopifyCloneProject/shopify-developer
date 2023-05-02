@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.orderProduct.title_singular') }}
    </div>

    <div class="card-body">
        {{-- <form method="POST" action="{{ route("admin.order-products.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="order_id">{{ trans('cruds.orderProduct.fields.order') }}</label>
                <select class="form-control select2 {{ $errors->has('order') ? 'is-invalid' : '' }}" name="order_id" id="order_id">
                    @foreach($orders as $id => $entry)
                        <option value="{{ $id }}" {{ old('order_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('order'))
                    <span class="text-danger">{{ $errors->first('order') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.order_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="product_id">{{ trans('cruds.orderProduct.fields.product') }}</label>
                <select class="form-control select2 {{ $errors->has('product') ? 'is-invalid' : '' }}" name="product_id" id="product_id">
                    @foreach($products as $id => $entry)
                        <option value="{{ $id }}" {{ old('product_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('product'))
                    <span class="text-danger">{{ $errors->first('product') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.product_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="user_id">{{ trans('cruds.orderProduct.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id">
                    @foreach($user as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <span class="text-danger">{{ $errors->first('user') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ trans('cruds.orderProduct.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email') }}" required>
                @if($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="mobile">{{ trans('cruds.orderProduct.fields.mobile') }}</label>
                <input class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}" type="number" name="mobile" id="mobile" value="{{ old('mobile') }}" required>
                @if($errors->has('mobile'))
                    <span class="text-danger">{{ $errors->first('mobile') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="variantoptions_id">{{ trans('cruds.orderProduct.fields.product_variant_options') }}</label>
                <select class="form-control select2 {{ $errors->has('variantoptions') ? 'is-invalid' : '' }}" name="variantoptions_id" id="variantoptions_id">
                  
                </select>
                @if($errors->has('variantoptions'))
                    <span class="text-danger">{{ $errors->first('variantoptions') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.product_variant_options_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.orderProduct.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="slug">{{ trans('cruds.orderProduct.fields.slug') }}</label>
                <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="slug" value="{{ old('slug', '') }}" required>
                @if($errors->has('slug'))
                    <span class="text-danger">{{ $errors->first('slug') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.slug_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="price">{{ trans('cruds.orderProduct.fields.price') }}</label>
                <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="text" name="price" id="price" value="{{ old('price', '') }}" required>
                @if($errors->has('price'))
                    <span class="text-danger">{{ $errors->first('price') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.price_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="quantity">{{ trans('cruds.orderProduct.fields.quantity') }}</label>
                <input class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}" type="text" name="quantity" id="quantity" value="{{ old('quantity', '') }}" required>
                @if($errors->has('quantity'))
                    <span class="text-danger">{{ $errors->first('quantity') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.quantity_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="sku">{{ trans('cruds.orderProduct.fields.sku') }}</label>
                <input class="form-control {{ $errors->has('sku') ? 'is-invalid' : '' }}" type="text" name="sku" id="sku" value="{{ old('sku', '') }}" required>
                @if($errors->has('sku'))
                    <span class="text-danger">{{ $errors->first('sku') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.sku_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="barcode">{{ trans('cruds.orderProduct.fields.barcode') }}</label>
                <input class="form-control {{ $errors->has('barcode') ? 'is-invalid' : '' }}" type="text" name="barcode" id="barcode" value="{{ old('barcode', '') }}" required>
                @if($errors->has('barcode'))
                    <span class="text-danger">{{ $errors->first('barcode') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.barcode_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="weightmanages">{{ trans('cruds.orderProduct.fields.weight_type') }}</label>
                <select class="form-control select2 {{ $errors->has('weightmanages') ? 'is-invalid' : '' }}" name="weightmanages" id="weightmanages">
                    @foreach($weightmanages as $id => $entry)
                        <option value="{{ $id }}" {{ old('weightmanages') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('weightmanages'))
                    <span class="text-danger">{{ $errors->first('weightmanages') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.weight_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="weight">{{ trans('cruds.orderProduct.fields.weight') }}</label>
                <input class="form-control {{ $errors->has('weight') ? 'is-invalid' : '' }}" type="text" name="weight" id="weight" value="{{ old('weight', '') }}" required>
                @if($errors->has('weight'))
                    <span class="text-danger">{{ $errors->first('weight') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.weight_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="hs_code">{{ trans('cruds.orderProduct.fields.hs_code') }}</label>
                <input class="form-control {{ $errors->has('hs_code') ? 'is-invalid' : '' }}" type="text" name="hs_code" id="hs_code" value="{{ old('hs_code', '') }}" required>
                @if($errors->has('hs_code'))
                    <span class="text-danger">{{ $errors->first('hs_code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.hs_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="is_product_charge">{{ trans('cruds.orderProduct.fields.is_product_charge') }}</label>
                <input class="form-control {{ $errors->has('is_product_charge') ? 'is-invalid' : '' }}" type="text" name="is_product_charge" id="is_product_charge" value="{{ old('is_product_charge', '') }}" required>
                @if($errors->has('is_product_charge'))
                    <span class="text-danger">{{ $errors->first('is_product_charge') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.is_product_charge_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="is_track">{{ trans('cruds.orderProduct.fields.is_track') }}</label>
                <input class="form-control {{ $errors->has('is_track') ? 'is-invalid' : '' }}" type="text" name="is_track" id="is_track" value="{{ old('is_track', '') }}" required>
                @if($errors->has('is_track'))
                    <span class="text-danger">{{ $errors->first('is_track') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.is_track_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="is_special_product">{{ trans('cruds.orderProduct.fields.is_special_product') }}</label>
                <input class="form-control {{ $errors->has('is_special_product') ? 'is-invalid' : '' }}" type="text" name="is_special_product" id="is_special_product" value="{{ old('is_special_product', '') }}" required>
                @if($errors->has('is_special_product'))
                    <span class="text-danger">{{ $errors->first('is_special_product') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.is_special_product_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="special_price">{{ trans('cruds.orderProduct.fields.special_price') }}</label>
                <input class="form-control {{ $errors->has('special_price') ? 'is-invalid' : '' }}" type="text" name="special_price" id="special_price" value="{{ old('special_price', '') }}" required>
                @if($errors->has('special_price'))
                    <span class="text-danger">{{ $errors->first('special_price') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.orderProduct.fields.special_price_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form> --}}
    </div>
</div>



@endsection