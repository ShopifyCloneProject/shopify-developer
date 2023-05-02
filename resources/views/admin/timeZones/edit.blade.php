@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.timeZone.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.time-zones.update", [$timeZone->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.timeZone.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $timeZone->title) }}" required>
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.timeZone.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="timezone_value">{{ trans('cruds.timeZone.fields.timezone_value') }}</label>
                <input class="form-control {{ $errors->has('timezone_value') ? 'is-invalid' : '' }}" type="text" name="timezone_value" id="timezone_value" value="{{ old('timezone_value', $timeZone->timezone_value) }}" required>
                @if($errors->has('timezone_value'))
                    <span class="text-danger">{{ $errors->first('timezone_value') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.timeZone.fields.timezone_value_helper') }}</span>
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