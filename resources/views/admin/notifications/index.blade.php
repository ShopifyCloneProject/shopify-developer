@extends('layouts/contentLayoutMaster')
@section('title', 'Notifications')

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

@can('notification_create')
<input type="hidden" id="notification_create" value="1">
@endcan
@can('notification_edit')
<input type="hidden" id="notification_edit" value="1">
@endcan
@can('notification_delete')
<input type="hidden" id="notification_delete" value="1">
@endcan
@can('notification_export_access')
<input type="hidden" id="notification_export_access" value="1">
@endcan

@section('content')
@can('notification_access')
    <section id="column-search-datatable">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-datatable">
              <table class="dt-column-search table">
                <thead>
                  <tr>
                    <th></th>
                    <th>{{ trans('cruds.notifications.fields.id') }}</th>
                    <th>{{ trans('cruds.notifications.fields.title') }}</th>
                    <th>{{ trans('cruds.notifications.fields.description') }}</th>
                    <th>{{ trans('cruds.notifications.fields.category') }}</th>
                    <th>{{ trans('cruds.notifications.fields.status') }}</th>
                    <th>{{ trans('global.actions') }}</th>
                  </tr>
                  <tr class="bg-gradient-secondary">
                      <td></td>
                      <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.notifications.fields.id') }}">
                      </td>
                      <td>
                          <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.notifications.fields.title') }}">
                      </td>
                      <td>
                          <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.notifications.fields.description') }}">
                      </td>
                       <td>
                         <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\Notification::Category as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                          </select>
                      </td>
                      <td>
                          <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\Notification::STATUS_RADIO as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
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
     let getListingUrl = "{{ route('admin.notifications.index') }}"
       let createUrl = "{{ route('admin.notifications.create') }}"
       let deleteUrl = "{{ url('api/admin/notifications/delete') }}"
       let destroyUrl = "{{ url('api/admin/notifications/massdestroy') }}"
     
  </script>
  <script src="{{ asset(mix('adminassets/js/notifications/index.min.js')) }}"></script>
@endsection