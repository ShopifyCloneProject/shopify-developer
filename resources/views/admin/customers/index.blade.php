@extends('layouts/contentLayoutMaster')

@section('title', 'Customers')

@section('vendor-style')
   @include('admin/partials/datatableCss')
@endsection
@section('page-style')
     <style>
      .conditionx-list {
          font-size: 12px;
          line-height: 20px;
      }
   </style>
@endsection

@section('content')
<!-- @can('user_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.users.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
            </a>
        </div>
    </div>
@endcan -->

@can('user_create')
<input type="hidden" id="user_create" value="1">
@endcan
@can('user_edit')
<input type="hidden" id="user_edit" value="1">
@endcan
@can('user_delete')
<input type="hidden" id="user_delete" value="1">
@endcan
@can('user_export_access')
<input type="hidden" id="user_export_access" value="1">
@endcan
@section('content')
@can('user_access')
    <section id="column-search-datatable">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-datatable">
              <table class="dt-column-search table table-responsive">
                <thead>
                  <tr>
                    <th></th>
                    <th>{{ trans('cruds.user.fields.name') }}</th>
                    <th>{{ trans('cruds.user.fields.username') }}</th>
                    <th>{{ trans('cruds.user.fields.mobile') }}</th>
                    <th>{{ trans('cruds.user.fields.email') }}</th>
                    <th>{{ trans('cruds.user.fields.gender') }}</th>
                    <th>{{ trans('cruds.user.fields.blocked') }}</th>
                    <th>{{ trans('global.actions') }}</th>
                  </tr>
                  <tr class="bg-gradient-secondary">
                      <td></td>
                      
                      <td>
                          <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.user.fields.name') }}">
                      </td>
                      <td>
                          <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.user.fields.username') }}">
                      </td>
                       <td>
                          <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.user.fields.mobile') }}">
                      </td>
                       <td>
                          <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.user.fields.email') }}">
                      </td>
                      <td>
                        <select class="form-control-sm search">
                            <option value>{{ trans('global.all') }}</option>
                             @foreach(App\Models\User::GENDER_RADIO as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                              @endforeach
                        </select>
                      </td>
                      <td>
                          <select class="form-control-sm search">
                            <option value>{{ trans('global.all') }}</option>
                             @foreach(App\Models\User::BLOCKED_RADIO as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                              @endforeach
                        </select>
                      </td>
                     
                     
                      <td></td>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
  </section>
@endcan
@endsection

@section('vendor-script')
   @include('admin/partials/datatableJs')
@endsection
@section('page-script')
  <script type="text/javascript">
     let getListingUrl = "{{ route('admin.customers.index') }}"
       let createUrl = "{{ route('admin.customers.create') }}"
       let deleteUrl = "{{ url('admin/customers/delete') }}"
       let destroyUrl = "{{ url('admin/customers/massdestroy') }}"
     
  </script>
  <script src="{{ asset(mix('adminassets/js/customer/index.min.js')) }}"></script>
@endsection



