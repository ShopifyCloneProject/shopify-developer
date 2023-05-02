@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.giftCardTag.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.gift-card-tags.update", [$giftCardTag->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="gift_card_id">{{ trans('cruds.giftCardTag.fields.gift_card') }}</label>
                <select class="form-control select2 {{ $errors->has('gift_card') ? 'is-invalid' : '' }}" name="gift_card_id" id="gift_card_id">
                    @foreach($gift_cards as $id => $entry)
                        <option value="{{ $id }}" {{ (old('gift_card_id') ? old('gift_card_id') : $giftCardTag->gift_card->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('gift_card'))
                    <span class="text-danger">{{ $errors->first('gift_card') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.giftCardTag.fields.gift_card_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tag_id">{{ trans('cruds.giftCardTag.fields.tag') }}</label>
                <select class="form-control select2 {{ $errors->has('tag') ? 'is-invalid' : '' }}" name="tag_id" id="tag_id">
                    @foreach($tags as $id => $entry)
                        <option value="{{ $id }}" {{ (old('tag_id') ? old('tag_id') : $giftCardTag->tag->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('tag'))
                    <span class="text-danger">{{ $errors->first('tag') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.giftCardTag.fields.tag_helper') }}</span>
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