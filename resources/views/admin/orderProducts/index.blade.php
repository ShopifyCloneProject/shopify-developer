@extends('layouts/contentLayoutMaster')
@section('title','Order Product')
@section('vendor-style')
   @include('admin/partials/datatableCss')
@endsection
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
<style>
    .alert-msg{
        margin-left: auto;
    }
    .alert{
        padding: 20px;
    }
    .alert strong{
        font-size: 16px;
        letter-spacing: 0.5px;
    }
</style>

@endsection
@section('content')


@can('order_product_create')
<input type="hidden" id="order_product_create" value="1">
@endcan
@can('order_product_edit')
<input type="hidden" id="order_product_edit" value="1">
@endcan
@can('order_product_show')
<input type="hidden" id="order_product_show" value="1">
@endcan
@can('order_product_delete')
<input type="hidden" id="order_product_delete" value="1">
@endcan
@can('order_product_export')
<input type="hidden" id="order_product_export" value="1">
@endcan


<section id="column-search-datatable">
  @can('order_product_access')
      <div class="row">

        <div class="col-12 alert-msg">
              @if ($message = Session::get('success'))
              <div class="alert alert-success alert-block">
              <button type="button" class="close" data-dismiss="alert">×</button>
              <strong>{{ $message }}</strong>
              </div>
              @endif
              @if ($message = Session::get('error'))
              <div class="alert alert-danger alert-block">
              <button type="button" class="close" data-dismiss="alert">×</button>
              <strong>{{ $message }}</strong>
              </div>
              @endif
        </div>

        <div class="col-12">
          <div class="card">
            <div class="card-header border-bottom">
              <h4 class="card-title"> {{ trans('cruds.orderProduct.title') }}</h4>
            </div>
            <div class="card-datatable">
              <table class="dt-column-search table table-responsive">
                <thead>
                  <tr>
                      <th>
                    </th>
                    <th>
                        {{ trans('cruds.orderProduct.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderProduct.fields.order') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderProduct.fields.product') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderProduct.fields.user') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderProduct.fields.email') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderProduct.fields.mobile') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderProduct.fields.product_variant_options') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderProduct.fields.title') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderProduct.fields.slug') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderProduct.fields.price') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderProduct.fields.quantity') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderProduct.fields.sku') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderProduct.fields.barcode') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderProduct.fields.weight_type') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderProduct.fields.weight') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderProduct.fields.hs_code') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderProduct.fields.is_product_charge') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderProduct.fields.is_track') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderProduct.fields.is_special_product') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderProduct.fields.special_price') }}
                    </th>
                    <th>
                        {{ trans('global.actions') }}
                    </th>
                  </tr>
                  <tr class="bg-gradient-secondary">
                        <td>
                        </td>
                        <td>
                            <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.orderProduct.fields.id') }}">
                        </td>
                        <td>
                            <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.orderProduct.fields.order') }}">
                        </td>
                        <td>
                           <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.orderProduct.fields.product') }}">
                        </td>
                        <td>
                           <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.orderProduct.fields.user') }}">
                        </td>
                        <td>
                           <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.orderProduct.fields.email') }}">
                        </td>
                        <td>
                           <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.orderProduct.fields.mobile') }}">
                        </td>
                        <td>
                           <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.orderProduct.fields.product_variant_options') }}">
                        </td>
                        <td>
                           <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.orderProduct.fields.title') }}">
                        </td>
                        <td>
                           <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.orderProduct.fields.slug') }}">
                        </td>
                        <td>
                           <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.orderProduct.fields.price') }}">
                        </td>
                        <td>
                           <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.orderProduct.fields.quantity') }}">
                        </td>
                        <td>
                           <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.orderProduct.fields.sku') }}">
                        </td>
                        <td>
                           <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.orderProduct.fields.barcode') }}">
                        </td>
                        <td>
                           <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.orderProduct.fields.weight_type') }}">
                        </td>
                        <td>
                           <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.orderProduct.fields.weight') }}">
                        </td>
                        <td>
                           <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.orderProduct.fields.hs_code') }}">
                        </td>
                        <td>
                           <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.orderProduct.fields.is_product_charge') }}">
                        </td>
                        <td>
                           <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.orderProduct.fields.is_track') }}">
                        </td>
                        <td>
                           <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.orderProduct.fields.is_special_product') }}">
                        </td>
                        <td>
                           <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.orderProduct.fields.special_price') }}">
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
     let getListingUrl = "{{ route('admin.order-products.index') }}";
     let createUrl = "{{ route('admin.order-products.create') }}";
     let deleteUrl = "{{ url('api/admin/order-products/delete') }}"
     let destroyUrl = "{{ url('api/admin/order-products/massdestroy') }}"
     let displayUrl = "{{ url('admin/order-products') }}";
    </script>

 <script src="{{ asset(mix('adminassets/js/orderproduct/orderproduct.min.js')) }}"></script>

@endsection