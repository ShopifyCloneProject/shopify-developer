@extends('layouts/contentLayoutMaster')

@section('title', 'Add Cart')

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
@can('add_cart_create')
<input type="hidden" id="add_cart_create" value="1">
@endcan
@can('add_cart_edit')
<input type="hidden" id="add_cart_edit" value="1">
@endcan
@can('add_cart_delete')
<input type="hidden" id="add_cart_delete" value="1">
@endcan
@can('add_cart_export')
<input type="hidden" id="add_cart_export" value="1">
@endcan

@section('content')
@can('add_cart_access')
    <section id="column-search-datatable">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-datatable">
              <table class="dt-column-search table">
                <thead>
                  <tr>
                    <th></th>
                    <th>{{ trans('cruds.cart.fields.id') }}</th>
                    <th>{{ trans('cruds.cart.fields.user') }}</th>
                    <th>{{ trans('cruds.cart.fields.email') }}</th>
                    <th>{{ trans('cruds.cart.fields.mobile') }}</th>
                    <th>{{ trans('global.actions') }}</th>
                  </tr>
                  <tr class="bg-gradient-secondary">
                      <td></td>
                      <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.cart.fields.id') }}">
                      </td>
                      <td>
                          <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.cart.fields.user') }}">
                      </td>
                      <td>
                          <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.cart.fields.email') }}">
                      </td>
                      <td>
                          <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.cart.fields.mobile') }}">
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
     let getListingUrl = "{{ route('admin.cart.index') }}";
     let createUrl = "{{ route('admin.cart.create') }}";
     let deleteUrl = "{{ url('api/admin/carts/delete') }}"
     let destroyUrl = "{{ url('api/admin/carts/massdestroy') }}"
  </script>
  <script src="{{ asset(mix('adminassets/js/cart/cart.min.js')) }}"></script>
@endsection