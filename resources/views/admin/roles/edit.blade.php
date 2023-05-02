@extends('layouts/contentLayoutMaster')
@section('vendor-style')
  <!-- vendor css files -->
   <style>
      .btn_action{
        text-align: right !important;
      }
      .btn-save{
        margin-right: 50px !important;
      }
  </style>
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">

@endsection

@section('content')
<section id="input-sizing">
    <form method="POST" action="{{ route("admin.roles.update", [$role->id]) }}" id="frmAddEditRole">
       @csrf
       <!-- 1=add, 2= edit -->
       <input type="hidden" value="2" id="addEditType">
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
                                    <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $role->title) }}" required>
                                    @if($errors->has('title'))
                                        <span class="text-danger">{{ $errors->first('title') }}</span>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.role.fields.title_helper') }}</span>
                                </div>
                                  <div class="form-group">
                                    <label>{{ trans('cruds.role.fields.status') }}</label>
                                    <select class="form-control" name="status" id="status" >
                                       @foreach(App\Models\Currency::STATUS_RADIO as $key => $label)
                                        <option value="{{ $key }}" {{ old('status', $role->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                       @endforeach
                                     </select>
                                </div>
                                <div class="form-group">
                                    <label class="required" for="permissions">{{ trans('cruds.role.fields.permissions') }}</label>
                                    <div style="padding-bottom: 4px">
                                        <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                        <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                                    </div>
                                    <select class="form-control select2 {{ $errors->has('permissions') ? 'is-invalid' : '' }}" name="permissions[]" id="permissions" multiple>
                                        @foreach($permissions as $id => $permissions)
                                            <option value="{{ $id }}" {{ (in_array($id, old('permissions', [])) || $role->permissions->contains($id)) ? 'selected' : '' }}>{{ $permissions }}</option>
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

        <div class="form-group">
            <button class="btn btn-danger" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>
    </form>
</section>
@endsection


@section('vendor-script')
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

@section('page-script')
  <script type="text/javascript">
    let storeUrl = "{{ route('admin.roles.store') }}";
  </script>
  <script src="{{ asset(mix('adminassets/js/role/create.min.js')) }}"></script>
@endsection