@extends('layouts/contentLayoutMaster')

@section('title', 'Shipping Products')

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
@can('return_shipping_product_show')
<input type="hidden" id="return_shipping_product_show" value="1">
@endcan
@can('return_shipping_product_export')
<input type="hidden" id="return_shipping_product_export" value="1">
@endcan
@can('return_shipping_product_delete')
<input type="hidden" id="return_shipping_product_delete" value="1">
@endcan


@section('content')
@can('return_shipping_product_access')
    <section id="column-search-datatable">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-datatable">
              <table class="dt-column-search table">
                <thead>
                  <tr>
                    <th></th>
                    <th>{{ trans('cruds.returnshippingproducts.fields.id') }}</th>
                    <th>{{ trans('cruds.returnshippingproducts.fields.orders') }}</th>
                    <th>{{ trans('cruds.returnshippingproducts.fields.customer') }}</th>
                    <th>{{ trans('cruds.returnshippingproducts.fields.mobile') }}</th>
                    <th>{{ trans('cruds.returnshippingproducts.fields.shipping') }}</th>
                    <th>{{ trans('cruds.returnshippingproducts.fields.approved') }}</th>
                    <th>{{ trans('cruds.returnshippingproducts.fields.order_id') }}</th>
                    <th>{{ trans('global.actions') }}</th>
                  </tr>
                   <tr class="bg-gradient-secondary">
                    <td></td>
                    <td>
                      <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.returnshippingproducts.fields.id') }}">
                    </td>
                    <td>
                      <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.returnshippingproducts.fields.orders') }}">
                    </td>
                    <td>
                      <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.returnshippingproducts.fields.customer') }}">
                    </td>
                    <td>
                      <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.returnshippingproducts.fields.mobile') }}">
                    </td>
                    <td>
                      <select class="form-control-sm search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($shippings as $key => $item)
                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                      <select class="form-control-sm search">
                            <option value>{{ trans('global.all') }}</option>
                            <option value="1">{{ trans('global.approved') }}</option>
                            <option value="0">{{ trans('global.not_approved') }}</option>
                      </select>
                    </td>
                    <td></td>
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
      let getListingUrl = "{{ route('admin.returnshippingproducts.index') }}";
      let handleActionUrl = "{{ route('admin.handleReturnShippingActions') }}";
  let deleteUrl = "{{ route('admin.returnshippingproducts.massDestroy') }}";
      
  </script>
  <script src="{{ asset(mix('adminassets/js/returnshippingproducts/returnshippingproducts.min.js')) }}"></script>
@endsection