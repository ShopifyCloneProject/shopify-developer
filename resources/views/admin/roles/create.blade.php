@extends('layouts/contentLayoutMaster')
@section('vendor-style')
  <!-- vendor css files -->
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <style>
      .btn_action{
        text-align: right;
      }
      .btn-save{
        margin-right: 50px;
      }
  </style>
@endsection

@section('content')
<section id="input-sizing">
   <form method="POST" action="{{ route('admin.roles.store') }}" id="frmAddEditRole">
       @csrf
       <input type="hidden" value="1" id="addEditType">
       <div class="row">
            <div class="col-md-8 col-12">
                <!-- Basic details start -->
                <div class="card">
                    <div class="card-header">
                        {{ trans('global.create') }} {{ trans('cruds.role.title_singular') }}
                    </div>
                    <div class="card-body">
                       <div class="row">
                          <div class="col-12">
                                <div class="form-group">
                                    <label class="required" for="title">{{ trans('cruds.role.fields.title') }}</label>
                                    <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                                    @if($errors->has('title'))
                                        <span class="text-danger">{{ $errors->first('title') }}</span>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.role.fields.title_helper') }}</span>
                                </div>

                                  <div class="form-group">
                                    <label>{{ trans('cruds.role.fields.status') }}</label>
                                    <select class="form-control" name="status" id="status" >
                                       @foreach(App\Models\Currency::STATUS_RADIO as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                       @endforeach
                                     </select>
                                </div>

                                <div class="form-group">
                                    <label class="required" for="permissions">{{ trans('cruds.role.fields.permissions') }}</label>
                                    <div style="padding-bottom: 4px">
                                        <span class="btn btn-primary waves-effect waves-float waves-light select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                        <span class="btn btn-danger waves-effect waves-float waves-light deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                                    </div>
                                    <select class="form-control select2 {{ $errors->has('permissions') ? 'is-invalid' : '' }}" name="permissions[]" id="permissions" multiple style="max-height: 200px">
                                        @foreach($permissions as $id => $permissions)
                                            <option value="{{ $id }}" {{ in_array($id, old('permissions', [])) ? 'selected' : '' }}>{{ $permissions }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('permissions'))
                                        <span class="text-danger">{{ $errors->first('permissions') }}</span>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.role.fields.permissions_helper') }}</span>
                                </div>
                                
                          </div>
                       </div>
                    </div>
                </div>
                <!-- Basic details end -->
            </div>

        </div>

        <div class="form-group btn_action text-left">
            <button class="btn btn-danger btn-save" type="submit">
                {{ trans('global.save') }}
            </button>
            <button class="btn btn-danger" type="reset" id="reset" style="display: none">Reset</button>
        </div>
    </form>
</section>
@endsection

@section('vendor-script')
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

@section('page-script')
  <script src="{{ asset(mix('adminassets/js/role/create.min.js')) }}"></script>
@endsection