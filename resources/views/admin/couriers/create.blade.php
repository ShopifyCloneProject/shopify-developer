@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.couriers.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.couriers.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.couriers.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.couriers.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="courier_code">{{ trans('cruds.couriers.fields.courier_code') }}</label>
                <input class="form-control {{ $errors->has('courier_code') ? 'is-invalid' : '' }}" type="text" name="courier_code" id="courier_code" value="{{ old('courier_code', '') }}" required>
                @if($errors->has('courier_code'))
                    <span class="text-danger">{{ $errors->first('courier_code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.couriers.fields.courier_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.couriers.fields.status') }}</label>
                @foreach(App\Models\Courier::STATUS_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="status_{{ $key }}" name="status" value="{{ $key }}" {{ old('status', '1') === (string) $key ? 'checked' : '' }} required>
                        <label class="form-check-label" for="status_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.couriers.fields.status_helper') }}</span>
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