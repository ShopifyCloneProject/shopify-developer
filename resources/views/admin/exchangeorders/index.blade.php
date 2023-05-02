@extends('layouts/contentLayoutMaster')

@section('title', 'Exchange Orders')

@section('vendor-style')
   @include('admin/partials/datatableCss')
@endsection
@section('page-style')
   <style>
      .conditionx-list {
          font-size: 12px;
          line-height: 20px;
      }
      .custom-switch .custom-control-label .switch-icon-left svg{
        height: 23px;
      }
      .custom-switch .custom-control-label .switch-icon-right svg{
        height: 23px;
      }
   </style>
@endsection
@can('exchangeorders_show')
<input type="hidden" id="exchangeorders_show" value="1">
@endcan
@can('exchangeorders_delete')
<input type="hidden" id="exchangeorders_delete" value="1">
@endcan
@can('exchangeorders_export')
<input type="hidden" id="exchangeorders_export" value="1">
@endcan
@can('return_exchangeorders')
<input type="hidden" id="return_exchangeorders" value="1">
@endcan

@section('content')
@can('exchangeorders_access')
    <section id="column-search-datatable">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-datatable">
              <table class="dt-column-search table table-responsive">
                <thead>
                  <tr>
                    <th></th>
                    <th>{{ trans('cruds.exchangeorders.fields.orders') }}</th>
                    <th>{{ trans('cruds.exchangeorders.fields.customer') }}</th>
                    <th>{{ trans('cruds.exchangeorders.fields.mobile') }}</th>
                    <th>{{ trans('cruds.exchangeorders.fields.approved') }}</th>
                    <th>{{ trans('global.actions') }}</th>
                  </tr>
                  <tr class="bg-gradient-secondary">
                    <td></td>
                    <td>
                      <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.exchangeorders.fields.orders') }}">
                    </td>
                    <td>
                      <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.exchangeorders.fields.customer') }}">
                    </td>
                    <td>
                      <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.exchangeorders.fields.mobile') }}">
                    </td>
                    <td>
                      <select class="form-control-sm search">
                            <option value>{{ trans('global.all') }}</option>
                            <option value="1">{{ trans('global.approved') }}</option>
                            <option value="0">{{ trans('global.not_approved') }}</option>
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
      let getListingUrl = "{{ route('admin.exchangeorders.index') }}";
      let exchangeOrderUrl = "{{ url('admin/returnexchangeorders') }}";
      let deleteUrl = "{{ route('admin.exchangeorders.massDestroy') }}";
  </script>
  <script src="{{ asset(mix('adminassets/js/exchangeorders/exchangeorders.min.js')) }}"></script>
@endsection