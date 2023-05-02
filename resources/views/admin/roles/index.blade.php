@extends('layouts/contentLayoutMaster')

@section('vendor-style')
   @include('admin/partials/datatableCss')
@endsection
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
@endsection

@section('content')
<!-- @can('role_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.roles.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.role.title_singular') }}
            </a>
        </div>
    </div>
@endcan -->

@can('role_create')
<input type="hidden" id="role_create" value="1">
@endcan
@can('role_edit')
<input type="hidden" id="role_edit" value="1">
@endcan
@can('role_delete')
<input type="hidden" id="role_delete" value="1">
@endcan
@can('role_export_access')
<input type="hidden" id="role_export_access" value="1">
@endcan

<section id="column-search-datatable">
@can('role_access')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header border-bottom">
          <h4 class="card-title">{{ trans('cruds.role.title') }}</h4>
        </div>
        <div class="card-datatable">
          <table class="dt-column-search table">
            <thead>
              <tr>
                  <th></th>
                  <th>{{ trans('cruds.role.fields.id') }}</th>
                  <th>{{ trans('cruds.role.fields.title') }}</th>
                  <th>{{ trans('global.status') }}</th>
                  <th>{{ trans('global.actions') }}</th>
              </tr>
              <tr class="bg-gradient-secondary">
                    <td>
                    </td>
                    <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.role.fields.id') }}">
                    </td>
                    <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.role.title_singular') }}">
                    </td>
                    <td>
                        <select class="form-control-sm search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\Role::STATUS_RADIO as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                    </td>
                </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
@endcan
</section>
@endsection

@section('vendor-script')
  @include('admin/partials/datatableJs')
@endsection

@section('page-script')
   <script type="text/javascript">
      let getListingUrl = "{{ route('admin.roles.index') }}";
      let deleteUrl = "{{ route('admin.roles.massDestroy') }}";
      let createUrl = "{{ route('admin.roles.create') }}";
   </script>
   <script src="{{ asset(mix('adminassets/js/role/role.min.js')) }}"></script>
@endsection