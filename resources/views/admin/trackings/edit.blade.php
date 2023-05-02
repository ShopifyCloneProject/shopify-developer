@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.trackings.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.trackings.update", [$trackings->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.trackings.fields.description') }}</label>
                <input class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" type="text" name="description" id="description" value="{{ old('description', $trackings->description) }}" required>
                @if($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.trackings.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="status_code">{{ trans('cruds.trackings.fields.status_code') }}</label>
                <input class="form-control {{ $errors->has('status_code') ? 'is-invalid' : '' }}" type="text" name="status_code" id="status_code" value="{{ old('status_code', $trackings->status_code) }}" required>
                @if($errors->has('status_code'))
                    <span class="text-danger">{{ $errors->first('status_code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.trackings.fields.status_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.trackings.fields.status') }}</label>
                @foreach(App\Models\Tracking::STATUS_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="status_{{ $key }}" name="status" value="{{ $key }}" {{ old('status', $trackings->status) === (string) $key ? 'checked' : '' }} required>
                        <label class="form-check-label" for="status_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.trackings.fields.status_helper') }}</span>
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