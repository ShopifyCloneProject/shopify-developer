@extends('layouts/contentLayoutMaster')

@section('title', 'Themes')

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

@can('theme_create')
<input type="hidden" id="theme_create" value="1">
@endcan
@can('theme_edit')
<input type="hidden" id="theme_edit" value="1">
@endcan
@can('theme_delete')
<input type="hidden" id="theme_delete" value="1">
@endcan
@can('theme_export_access')
<input type="hidden" id="theme_export_access" value="1">
@endcan

@section('content')
@can('theme_access')
    <section id="column-search-datatable">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-datatable">
              <table class="dt-column-search table">
                <thead>
                  <tr>
                    <th></th>
                    <th>{{ trans('cruds.themes.fields.id') }}</th>
                    <th>{{ trans('cruds.themes.fields.name') }}</th>
                    <th>{{ trans('cruds.themes.fields.image') }}</th>
                    <th>{{ trans('cruds.themes.fields.themeurl') }}</th>
                    <th>{{ trans('global.actions') }}</th>
                  </tr>
                  <tr class="bg-gradient-secondary">
                      <td></td>
                      <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.themes.fields.id') }}">
                      </td>
                      <td>
                          <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.themes.fields.name') }}">
                      </td>
                      <td>
                          <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.themes.fields.image') }}">
                      </td>
                       <td>
                          <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.themes.fields.themeurl') }}">
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
     let getListingUrl = "{{ route('admin.themes.index') }}";
     let createUrl = "{{ route('admin.themes.create') }}";
     let deleteUrl = "{{ url('api/admin/themes/delete') }}"
     let destroyUrl = "{{ url('api/admin/themes/massdestroy') }}"
  </script>
  <script src="{{ asset(mix('adminassets/js/themes/index.min.js')) }}"></script>
@endsection