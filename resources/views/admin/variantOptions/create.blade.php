@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.variantOption.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.variant-options.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="options">{{ trans('cruds.variantOption.fields.options') }}</label>
                <input class="form-control {{ $errors->has('options') ? 'is-invalid' : '' }}" type="text" name="options" id="options" value="{{ old('options', '') }}" required>
                @if($errors->has('options'))
                    <span class="text-danger">{{ $errors->first('options') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.variantOption.fields.options_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.variantOption.fields.status') }}</label>
                @foreach(App\Models\VariantOption::STATUS_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="status_{{ $key }}" name="status" value="{{ $key }}" {{ old('status', '1') === (string) $key ? 'checked' : '' }} required>
                        <label class="form-check-label" for="status_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.variantOption.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="variant_id">{{ trans('cruds.variantOption.fields.variant') }}</label>
                <select class="form-control select2 {{ $errors->has('variant') ? 'is-invalid' : '' }}" name="variant_id" id="variant_id" required>
                    @foreach($variants as $id => $entry)
                        <option value="{{ $id }}" {{ old('variant_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('variant'))
                    <span class="text-danger">{{ $errors->first('variant') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.variantOption.fields.variant_helper') }}</span>
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