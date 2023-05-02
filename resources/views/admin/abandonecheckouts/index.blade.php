@extends('layouts/contentLayoutMaster')

@section('title', 'Abandone Checkouts')

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
@can('abandone_checkout_show')
<input type="hidden" id="abandone_checkout_show" value="1">
@endcan
@can('abandone_checkout_export')
<input type="hidden" id="abandone_checkout_export" value="1">
@endcan

@section('content')
@can('abandone_checkout_access')
    <section id="column-search-datatable">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-datatable">
              <table class="dt-column-search table">
                <thead>
                  <tr>
                    <th></th>
                    <th>{{ trans('cruds.abandonecheckouts.fields.id') }}</th>
                    <th>{{ trans('cruds.abandonecheckouts.fields.user') }}</th>
                    <th>{{ trans('cruds.abandonecheckouts.fields.email') }}</th>
                    <th>{{ trans('cruds.abandonecheckouts.fields.mobile') }}</th>
                    <th>{{ trans('global.actions') }}</th>
                  </tr>
                  <tr class="bg-gradient-secondary">
                      <td></td>
                      <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.abandonecheckouts.fields.id') }}">
                      </td>
                      <td>
                          <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.abandonecheckouts.fields.user') }}">
                      </td>
                      <td>
                          <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.abandonecheckouts.fields.email') }}">
                      </td>
                      <td>
                          <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.abandonecheckouts.fields.mobile') }}">
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
      let getListingUrl = "{{ route('admin.abandonecheckouts.index') }}";
      let displayUrl = "{{ url('admin/abandonecheckouts') }}";
  </script>
  <script src="{{ asset(mix('adminassets/js/abandonecheckouts/abandonecheckouts.min.js')) }}"></script>
@endsection