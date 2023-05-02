@extends('layouts/contentLayoutMaster')
@section('title','Order Product Variant')
@section('vendor-style')
   @include('admin/partials/datatableCss')
@endsection
@section('page-style')
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

@can('order_product_variant_create')
<input type="hidden" id="order_product_variant_create" value="1">
@endcan
@can('order_product_variant_edit')
<input type="hidden" id="order_product_variant_edit" value="1">
@endcan
@can('order_product_variant_show')
<input type="hidden" id="order_product_variant_show" value="1">
@endcan
@can('order_product_variant_delete')
<input type="hidden" id="order_product_variant_delete" value="1">
@endcan
@can('order_product_variant_export')
<input type="hidden" id="order_product_variant_export" value="1">
@endcan

<section id="column-search-datatable">
  @can('order_product_variant_access')
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
              <h4 class="card-title"> {{ trans('cruds.orderProductVariant.title') }}</h4>
            </div>
            <div class="card-datatable">
              <table class="dt-column-search table table-responsive">
                <thead>
                  <tr>
                      <th>
                    </th>
                    <th>
                        {{ trans('cruds.orderProductVariant.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderProductVariant.fields.order_detail') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderProductVariant.fields.product_variant') }}
                    </th>
                    <th>
                        {{ trans('global.actions') }}
                    </th>
                  </tr>
                  <tr class="bg-gradient-secondary">
                        <td>
                        </td>
                        <td>
                            <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.orderProductVariant.fields.id') }}">
                        </td>
                        <td>
                            <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.orderProductVariant.fields.order_detail') }}">
                        </td>
                        <td>
                            <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.orderProductVariant.fields.product_variant') }}">
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
     let getListingUrl = "{{ route('admin.order-product-variants.index') }}";
     let createUrl = "{{ route('admin.order-product-variants.create') }}";
     let deleteUrl = "{{ url('api/admin/order-product-variants/delete') }}"
     let destroyUrl = "{{ url('api/admin/order-product-variants/massdestroy') }}"
     let displayUrl = "{{ url('admin/order-product-variants') }}";
    </script>

 <script src="{{ asset(mix('adminassets/js/orderproductvariants/orderproductvariants.min.js')) }}"></script>

@endsection
