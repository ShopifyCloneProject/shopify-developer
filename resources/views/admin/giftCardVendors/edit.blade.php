@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.giftCardVendor.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.gift-card-vendors.update", [$giftCardVendor->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="gift_card_id">{{ trans('cruds.giftCardVendor.fields.gift_card') }}</label>
                <select class="form-control select2 {{ $errors->has('gift_card') ? 'is-invalid' : '' }}" name="gift_card_id" id="gift_card_id">
                    @foreach($gift_cards as $id => $entry)
                        <option value="{{ $id }}" {{ (old('gift_card_id') ? old('gift_card_id') : $giftCardVendor->gift_card->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('gift_card'))
                    <span class="text-danger">{{ $errors->first('gift_card') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.giftCardVendor.fields.gift_card_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="vendor_id">{{ trans('cruds.giftCardVendor.fields.vendor') }}</label>
                <select class="form-control select2 {{ $errors->has('vendor') ? 'is-invalid' : '' }}" name="vendor_id" id="vendor_id">
                    @foreach($vendors as $id => $entry)
                        <option value="{{ $id }}" {{ (old('vendor_id') ? old('vendor_id') : $giftCardVendor->vendor->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('vendor'))
                    <span class="text-danger">{{ $errors->first('vendor') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.giftCardVendor.fields.vendor_helper') }}</span>
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