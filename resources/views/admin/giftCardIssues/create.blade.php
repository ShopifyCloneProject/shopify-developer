@extends('layouts/contentLayoutMaster')
@section('title', 'Gift card issues')

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
        {{ trans('global.create') }} {{ trans('cruds.giftCardIssue.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.gift-card-issues.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="code">{{ trans('cruds.giftCardIssue.fields.code') }}</label>
                <input class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" type="text" name="code" id="code" value="{{ old('code', '') }}" required>
                @if($errors->has('code'))
                    <span class="text-danger">{{ $errors->first('code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.giftCardIssue.fields.code_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.giftCardIssue.fields.status') }}</label>
                @foreach(App\Models\GiftCardIssue::STATUS_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="status_{{ $key }}" name="status" value="{{ $key }}" {{ old('status', '1') === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.giftCardIssue.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="date_issued">{{ trans('cruds.giftCardIssue.fields.date_issued') }}</label>
                <input class="form-control flatpickr-date-time {{ $errors->has('date_issued') ? 'is-invalid' : '' }}" type="text" name="date_issued" id="date_issued" value="{{ old('date_issued') }}">
                @if($errors->has('date_issued'))
                    <span class="text-danger">{{ $errors->first('date_issued') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.giftCardIssue.fields.date_issued_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="remaining_value">{{ trans('cruds.giftCardIssue.fields.remaining_value') }}</label>
                <input class="form-control {{ $errors->has('remaining_value') ? 'is-invalid' : '' }}" type="number" name="remaining_value" id="remaining_value" value="{{ old('remaining_value', '') }}" step="0.01">
                @if($errors->has('remaining_value'))
                    <span class="text-danger">{{ $errors->first('remaining_value') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.giftCardIssue.fields.remaining_value_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="initial_value">{{ trans('cruds.giftCardIssue.fields.initial_value') }}</label>
                <input class="form-control {{ $errors->has('initial_value') ? 'is-invalid' : '' }}" type="number" name="initial_value" id="initial_value" value="{{ old('initial_value', '') }}" step="0.01">
                @if($errors->has('initial_value'))
                    <span class="text-danger">{{ $errors->first('initial_value') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.giftCardIssue.fields.initial_value_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.giftCardIssue.fields.expiration_type') }}</label>
                @foreach(App\Models\GiftCardIssue::EXPIRATION_TYPE_RADIO as $key => $label)
                    <div class="form-check  {{ $errors->has('expiration_type') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="expiration_type_{{ $key }}" name="expiration_type" value="{{ $key }}" {{ old('expiration_type', '1') === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="expiration_type_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('expiration_type'))
                    <span class="text-danger">{{ $errors->first('expiration_type') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.giftCardIssue.fields.expiration_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="expiration_date">{{ trans('cruds.giftCardIssue.fields.expiration_date') }}</label>
                <input class="form-control flatpickr-basic {{ $errors->has('expiration_date') ? 'is-invalid' : '' }}" type="text" name="expiration_date" id="expiration_date" value="{{ old('expiration_date') }}">
                @if($errors->has('expiration_date'))
                    <span class="text-danger">{{ $errors->first('expiration_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.giftCardIssue.fields.expiration_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="note">{{ trans('cruds.giftCardIssue.fields.note') }}</label>
                <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note">{{ old('note') }}</textarea>
                @if($errors->has('note'))
                    <span class="text-danger">{{ $errors->first('note') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.giftCardIssue.fields.note_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.giftCardIssue.fields.enabled') }}</label>
                @foreach(App\Models\GiftCardIssue::ENABLED_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('enabled') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="enabled_{{ $key }}" name="enabled" value="{{ $key }}" {{ old('enabled', '1') === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="enabled_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('enabled'))
                    <span class="text-danger">{{ $errors->first('enabled') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.giftCardIssue.fields.enabled_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="disabled_at">{{ trans('cruds.giftCardIssue.fields.disabled_at') }}</label>
                <input class="form-control flatpickr-date-time datetime {{ $errors->has('disabled_at') ? 'is-invalid' : '' }}" type="text" name="disabled_at" id="disabled_at" value="{{ old('disabled_at') }}">
                @if($errors->has('disabled_at'))
                    <span class="text-danger">{{ $errors->first('disabled_at') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.giftCardIssue.fields.disabled_at_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="user_id">{{ trans('cruds.giftCardIssue.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id">
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <span class="text-danger">{{ $errors->first('user') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.giftCardIssue.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="gift_card_id">{{ trans('cruds.giftCardIssue.fields.gift_card') }}</label>
                <select class="form-control select2 {{ $errors->has('gift_card') ? 'is-invalid' : '' }}" name="gift_card_id" id="gift_card_id">
                    @foreach($gift_cards as $id => $entry)
                        <option value="{{ $id }}" {{ old('gift_card_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('gift_card'))
                    <span class="text-danger">{{ $errors->first('gift_card') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.giftCardIssue.fields.gift_card_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="currency_id">{{ trans('cruds.giftCardIssue.fields.currency') }}</label>
                <select class="form-control select2 {{ $errors->has('currency') ? 'is-invalid' : '' }}" name="currency_id" id="currency_id">
                    @foreach($currencies as $id => $entry)
                        <option value="{{ $id }}" {{ old('currency_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('currency'))
                    <span class="text-danger">{{ $errors->first('currency') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.giftCardIssue.fields.currency_helper') }}</span>
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