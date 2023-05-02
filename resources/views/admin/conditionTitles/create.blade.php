@extends('layouts/contentLayoutMaster')
@section('content')
<section id="input-sizing">
    <form method="POST" action="{{ route("admin.condition-titles.store") }}" enctype="multipart/form-data">
       @csrf
       <div class="row">
            <div class="col-md-8 col-12">
                <!-- Basic details start -->
                <div class="card">
                    <div class="card-body">
                       <div class="row">
                          <div class="col-12">
                                <div class="form-group">
                                    <label class="required" for="title">{{ trans('cruds.conditionTitle.fields.title') }}</label>
                                    <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                                    @if($errors->has('title'))
                                        <span class="text-danger">{{ $errors->first('title') }}</span>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.conditionTitle.fields.title_helper') }}</span>
                                </div>
                               
                                <div class="form-group">
                                    <label class="required" for="collection_condition">{{ trans('cruds.conditionTitle.fields.collection_condition') }}</label>
                                    <input class="form-control {{ $errors->has('collection_condition') ? 'is-invalid' : '' }}" type="text" name="collection_condition" id="collection_condition" value="{{ old('collection_condition', '') }}" required>
                                    @if($errors->has('collection_condition'))
                                        <span class="text-danger">{{ $errors->first('collection_condition') }}</span>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.conditionTitle.fields.collection_condition_helper') }}</span>
                                </div>
                          </div>
                       </div>
                    </div>
                </div>
                <!-- Basic details end -->
            </div>

            <div class="col-md-4 col-12">
             <div class="card">
                <div class="card-header">
                   <h4 class="card-title">{{ trans('global.status') }}</h4>
                </div>
                <div class="card-body">
                   <div class="row">
                      <div class="col-12">
                         <p class="card-text mb-2">
                            Be sure to use <code>.col-form-label-sm</code> or <code>.col-form-label-lg</code> to your
                         </p>
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.conditionTitle.fields.status') }}</label>
                            @foreach(App\Models\ConditionTitle::STATUS_RADIO as $key => $label)
                                <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                                    <input class="form-check-input" type="radio" id="status_{{ $key }}" name="status" value="{{ $key }}" {{ old('status', '1') === (string) $key ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="status_{{ $key }}">{{ $label }}</label>
                                </div>
                            @endforeach
                            @if($errors->has('status'))
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.conditionTitle.fields.status_helper') }}</span>
                        </div>
                      </div>
                   </div>
                </div>
             </div>
          </div>
        </div>
    </form>
</section>



<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.conditionTitle.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.condition-titles.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.conditionTitle.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.conditionTitle.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.conditionTitle.fields.status') }}</label>
                @foreach(App\Models\ConditionTitle::STATUS_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="status_{{ $key }}" name="status" value="{{ $key }}" {{ old('status', '1') === (string) $key ? 'checked' : '' }} required>
                        <label class="form-check-label" for="status_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.conditionTitle.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="collection_condition">{{ trans('cruds.conditionTitle.fields.collection_condition') }}</label>
                <input class="form-control {{ $errors->has('collection_condition') ? 'is-invalid' : '' }}" type="text" name="collection_condition" id="collection_condition" value="{{ old('collection_condition', '') }}" required>
                @if($errors->has('collection_condition'))
                    <span class="text-danger">{{ $errors->first('collection_condition') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.conditionTitle.fields.collection_condition_helper') }}</span>
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