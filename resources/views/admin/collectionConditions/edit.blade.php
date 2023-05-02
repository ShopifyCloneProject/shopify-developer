@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.collectionCondition.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.collection-conditions.update", [$collectionCondition->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="model_name">{{ trans('cruds.collectionCondition.fields.model_name') }}</label>
                <input class="form-control {{ $errors->has('model_name') ? 'is-invalid' : '' }}" type="text" name="model_name" id="model_name" value="{{ old('model_name', $collectionCondition->model_name) }}">
                @if($errors->has('model_name'))
                    <span class="text-danger">{{ $errors->first('model_name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.collectionCondition.fields.model_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="model_ref">{{ trans('cruds.collectionCondition.fields.model_ref') }}</label>
                <input class="form-control {{ $errors->has('model_ref') ? 'is-invalid' : '' }}" type="text" name="model_ref" id="model_ref" value="{{ old('model_ref', $collectionCondition->model_ref) }}">
                @if($errors->has('model_ref'))
                    <span class="text-danger">{{ $errors->first('model_ref') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.collectionCondition.fields.model_ref_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.collectionCondition.fields.status') }}</label>
                @foreach(App\Models\CollectionCondition::STATUS_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="status_{{ $key }}" name="status" value="{{ $key }}" {{ old('status', $collectionCondition->status) === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.collectionCondition.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="value">{{ trans('cruds.collectionCondition.fields.value') }}</label>
                <input class="form-control {{ $errors->has('value') ? 'is-invalid' : '' }}" type="text" name="value" id="value" value="{{ old('value', $collectionCondition->value) }}">
                @if($errors->has('value'))
                    <span class="text-danger">{{ $errors->first('value') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.collectionCondition.fields.value_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="collection_title_id">{{ trans('cruds.collectionCondition.fields.collection_title') }}</label>
                <select class="form-control select2 {{ $errors->has('collection_title') ? 'is-invalid' : '' }}" name="collection_title_id" id="collection_title_id" required>
                    @foreach($collection_titles as $id => $entry)
                        <option value="{{ $id }}" {{ (old('collection_title_id') ? old('collection_title_id') : $collectionCondition->collection_title->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('collection_title'))
                    <span class="text-danger">{{ $errors->first('collection_title') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.collectionCondition.fields.collection_title_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="condition_id">{{ trans('cruds.collectionCondition.fields.condition') }}</label>
                <select class="form-control select2 {{ $errors->has('condition') ? 'is-invalid' : '' }}" name="condition_id" id="condition_id" required>
                    @foreach($conditions as $id => $entry)
                        <option value="{{ $id }}" {{ (old('condition_id') ? old('condition_id') : $collectionCondition->condition->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('condition'))
                    <span class="text-danger">{{ $errors->first('condition') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.collectionCondition.fields.condition_helper') }}</span>
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