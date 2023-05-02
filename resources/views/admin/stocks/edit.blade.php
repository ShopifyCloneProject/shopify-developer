@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.stock.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.stocks.update", [$stock->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="quantity">{{ trans('cruds.stock.fields.quantity') }}</label>
                <input class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}" type="number" name="quantity" id="quantity" value="{{ old('quantity', $stock->quantity) }}" step="1">
                @if($errors->has('quantity'))
                    <span class="text-danger">{{ $errors->first('quantity') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.stock.fields.quantity_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="available_quantity">{{ trans('cruds.stock.fields.available_quantity') }}</label>
                <input class="form-control {{ $errors->has('available_quantity') ? 'is-invalid' : '' }}" type="number" name="available_quantity" id="available_quantity" value="{{ old('available_quantity', $stock->available_quantity) }}" step="1">
                @if($errors->has('available_quantity'))
                    <span class="text-danger">{{ $errors->first('available_quantity') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.stock.fields.available_quantity_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="defect_quantity">{{ trans('cruds.stock.fields.defect_quantity') }}</label>
                <input class="form-control {{ $errors->has('defect_quantity') ? 'is-invalid' : '' }}" type="text" name="defect_quantity" id="defect_quantity" value="{{ old('defect_quantity', $stock->defect_quantity) }}">
                @if($errors->has('defect_quantity'))
                    <span class="text-danger">{{ $errors->first('defect_quantity') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.stock.fields.defect_quantity_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="product_id">{{ trans('cruds.stock.fields.product') }}</label>
                <select class="form-control select2 {{ $errors->has('product') ? 'is-invalid' : '' }}" name="product_id" id="product_id" required>
                    @foreach($products as $id => $entry)
                        <option value="{{ $id }}" {{ (old('product_id') ? old('product_id') : $stock->product->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('product'))
                    <span class="text-danger">{{ $errors->first('product') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.stock.fields.product_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="address_id">{{ trans('cruds.stock.fields.address') }}</label>
                <select class="form-control select2 {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address_id" id="address_id" required>
                    @foreach($addresses as $id => $entry)
                        <option value="{{ $id }}" {{ (old('address_id') ? old('address_id') : $stock->address->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('address'))
                    <span class="text-danger">{{ $errors->first('address') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.stock.fields.address_helper') }}</span>
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