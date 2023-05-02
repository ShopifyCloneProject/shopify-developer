@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.userStore.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.settings.user-stores.update", [$userStore->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="store_contact_email">{{ trans('cruds.userStore.fields.store_contact_email') }}</label>
                <input class="form-control {{ $errors->has('store_contact_email') ? 'is-invalid' : '' }}" type="email" name="store_contact_email" id="store_contact_email" value="{{ old('store_contact_email', $userStore->store_contact_email) }}" required>
                @if($errors->has('store_contact_email'))
                    <span class="text-danger">{{ $errors->first('store_contact_email') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.userStore.fields.store_contact_email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="sender_email">{{ trans('cruds.userStore.fields.sender_email') }}</label>
                <input class="form-control {{ $errors->has('sender_email') ? 'is-invalid' : '' }}" type="text" name="sender_email" id="sender_email" value="{{ old('sender_email', $userStore->sender_email) }}" required>
                @if($errors->has('sender_email'))
                    <span class="text-danger">{{ $errors->first('sender_email') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.userStore.fields.sender_email_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="company">{{ trans('cruds.userStore.fields.company') }}</label>
                <input class="form-control {{ $errors->has('company') ? 'is-invalid' : '' }}" type="text" name="company" id="company" value="{{ old('company', $userStore->company) }}">
                @if($errors->has('company'))
                    <span class="text-danger">{{ $errors->first('company') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.userStore.fields.company_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.userStore.fields.unit_system') }}</label>
                <select class="form-control {{ $errors->has('unit_system') ? 'is-invalid' : '' }}" name="unit_system" id="unit_system" required>
                    <option value disabled {{ old('unit_system', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\UserStore::UNIT_SYSTEM_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('unit_system', $userStore->unit_system) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('unit_system'))
                    <span class="text-danger">{{ $errors->first('unit_system') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.userStore.fields.unit_system_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.userStore.fields.unit_weight') }}</label>
                <select class="form-control {{ $errors->has('unit_weight') ? 'is-invalid' : '' }}" name="unit_weight" id="unit_weight" required>
                    <option value disabled {{ old('unit_weight', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\UserStore::UNIT_WEIGHT_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('unit_weight', $userStore->unit_weight) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('unit_weight'))
                    <span class="text-danger">{{ $errors->first('unit_weight') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.userStore.fields.unit_weight_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="prefix">{{ trans('cruds.userStore.fields.prefix') }}</label>
                <input class="form-control {{ $errors->has('prefix') ? 'is-invalid' : '' }}" type="text" name="prefix" id="prefix" value="{{ old('prefix', $userStore->prefix) }}">
                @if($errors->has('prefix'))
                    <span class="text-danger">{{ $errors->first('prefix') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.userStore.fields.prefix_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="suffix">{{ trans('cruds.userStore.fields.suffix') }}</label>
                <input class="form-control {{ $errors->has('suffix') ? 'is-invalid' : '' }}" type="text" name="suffix" id="suffix" value="{{ old('suffix', $userStore->suffix) }}">
                @if($errors->has('suffix'))
                    <span class="text-danger">{{ $errors->first('suffix') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.userStore.fields.suffix_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="address_id">{{ trans('cruds.userStore.fields.address') }}</label>
                <select class="form-control select2 {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address_id" id="address_id">
                    @foreach($addresses as $id => $entry)
                        <option value="{{ $id }}" {{ (old('address_id') ? old('address_id') : $userStore->address->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('address'))
                    <span class="text-danger">{{ $errors->first('address') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.userStore.fields.address_helper') }}</span>
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