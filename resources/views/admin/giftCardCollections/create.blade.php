@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.giftCardCollection.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.gift-card-collections.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="gift_card_id">{{ trans('cruds.giftCardCollection.fields.gift_card') }}</label>
                <select class="form-control select2 {{ $errors->has('gift_card') ? 'is-invalid' : '' }}" name="gift_card_id" id="gift_card_id">
                    @foreach($gift_cards as $id => $entry)
                        <option value="{{ $id }}" {{ old('gift_card_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('gift_card'))
                    <span class="text-danger">{{ $errors->first('gift_card') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.giftCardCollection.fields.gift_card_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="collection_id">{{ trans('cruds.giftCardCollection.fields.collection') }}</label>
                <select class="form-control select2 {{ $errors->has('collection') ? 'is-invalid' : '' }}" name="collection_id" id="collection_id">
                    @foreach($collections as $id => $entry)
                        <option value="{{ $id }}" {{ old('collection_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('collection'))
                    <span class="text-danger">{{ $errors->first('collection') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.giftCardCollection.fields.collection_helper') }}</span>
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