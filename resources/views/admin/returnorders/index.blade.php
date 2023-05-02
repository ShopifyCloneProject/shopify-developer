@extends('layouts/contentLayoutMaster')

@section('title', 'Return Orders')

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
@can('returnorders_show')
<input type="hidden" id="returnorders_show" value="1">
@endcan
@can('returnorders_refund')
<input type="hidden" id="returnorders_refund" value="1">
@endcan
@can('returnorders_delete')
<input type="hidden" id="returnorders_delete" value="1">
@endcan
@can('returnorders_export')
<input type="hidden" id="returnorders_export" value="1">
@endcan

@section('content')
@can('returnorders_access')
    <section id="column-search-datatable">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-datatable">
              <table class="dt-column-search table">
                <thead>
                  <tr>
                    <th></th>
                    <th>{{ trans('cruds.returnorders.fields.orders') }}</th>
                    <th>{{ trans('cruds.returnorders.fields.customer') }}</th>
                    <th>{{ trans('cruds.returnorders.fields.mobile') }}</th>
                    <th>{{ trans('cruds.returnorders.fields.approved') }}</th>
                    <th>{{ trans('global.actions') }}</th>
                  </tr>
                  <tr class="bg-gradient-secondary">
                    <td></td>
                    <td>
                      <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.returnorders.fields.orders') }}">
                    </td>
                    <td>
                      <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.returnorders.fields.customer') }}">
                    </td>
                    <td>
                      <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.returnorders.fields.mobile') }}">
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
      let getListingUrl = "{{ route('admin.returnorders.index') }}";
      let refundOrderUrl = "{{ url('admin/refundorder') }}";
      let returnOrderUrl = "{{ url('admin/refundorderproduct') }}";
      let deleteUrl = "{{ route('admin.returnorders.massDestroy') }}";
  </script>
  <script src="{{ asset(mix('adminassets/js/returnorders/returnorders.min.js')) }}"></script>
@endsection