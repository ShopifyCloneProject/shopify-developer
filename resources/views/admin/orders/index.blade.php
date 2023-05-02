@extends('layouts/contentLayoutMaster')

@section('title', 'Orders')

@section('vendor-style')
  {{-- vendor css files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/rowGroup.bootstrap4.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
  <style type="text/css">
      .head-label{
        padding: 0 !important;
      }
      .dataTables_filter label input{
        width: 40% !important;
      }
      .dataTables_filter label{
        width: 100% !important;
        font-size: 0;
      }
      div.dataTables_filter input {
        margin-left: auto !important;
        margin-right: 1.5rem !important;
      }
      .dataTables_length {
          float: left;
      }
      .dataTables_length .btn-group {
          margin-top: 1rem !important;
      }
      .clickable-row{
        cursor: pointer;
      }
      .clickable-row .dtfc-fixed-right,.clickable-row .dtfc-fixed-left {
          background: #f8f8f8;
      }
      .dtfc-fixed-right,.dtfc-fixed-left{
          z-index: 99;
      }
      .search-bg-color{
        background-color: #94989c;
      }

  </style>
@endsection

@section('content')
{{-- @can('product_create') 
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.products.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.order.title_singular') }}
            </a>
        </div>
    </div>
@endcan --}}

@can('order_export_access')
<input type="hidden" id="order_export_access" value="1">
@endcan
@can('order_create')
<input type="hidden" id="order_create" value="1">
@endcan
@can('order_edit')
<input type="hidden" id="order_edit" value="1">
@endcan
@can('order_show')
<input type="hidden" id="order_show" value="1">
@endcan
@can('order_delete')
<input type="hidden" id="order_delete" value="1">
@endcan
@can('order_refund')
<input type="hidden" id="order_refund" value="1">
@endcan

<section id="column-search-datatable">
@can('order_access')
  <div class="row">
    <div class="col-12">
      <div id="advanceFilter" class="d-none">
          <div class="btn-group btn-group-toggle" data-toggle="buttons">
              <label class="btn btn-outline-primary active">
                <input type="radio" class="advance-filter" name="advance_filter" id="advance_filter1" data-column="" value='all' checked /> {{ trans('global.all') }}
              </label>
              <label class="btn btn-outline-primary">
                <input type="radio" class="advance-filter" name="advance_filter" id="advance_filter2" data-column="6" value='unfulfilled'/> {{ trans('cruds.order.unfulfilled') }}
              </label>
              <label class="btn btn-outline-primary">
                <input type="radio" class="advance-filter" name="advance_filter" id="advance_filter3" data-column="5" value='unpaid'/> {{ trans('cruds.order.unpaid') }}
              </label>
              <label class="btn btn-outline-primary">
                <input type="radio" class="advance-filter" name="advance_filter" id="advance_filter4" data-column="7" value='open'/> {{ trans('cruds.order.open') }}
              </label>
              <label class="btn btn-outline-primary">
                <input type="radio" class="advance-filter" name="advance_filter" id="advance_filter5" data-column="7" value='archived'/> {{ trans('cruds.order.close') }}
              </label>
          </div>
      </div>

      <div id="advanceFilter1" class="d-none">
        <div class="demo-inline-spacing d-inline-flex pl-3">
            <div class="btn-group">
              <button
                type="button"
                class="btn btn-outline-primary dropdown-toggle"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
               >
              {{ trans('cruds.order.status') }}
              </button>
              <div class="dropdown-menu keep-open p-1">
                  @foreach(App\Models\Order::STATUS as $key => $item)
                  <div class="custom-control custom-radio m-25">
                    <input type="radio" id="customRadio_{{$key}}" name="status" class="custom-control-input status-radio" value="{{$key}}" data-column="7">
                    <label class="custom-control-label mt-0" for="customRadio_{{$key}}">{{$item}}</label>
                  </div>
                  @endforeach
                  <a class="dropdown-item pointer p-0 mt-1 small clear-payment-status-radio">{{ trans('global.clear') }}</a>
              </div>
            </div>

            <div class="btn-group">
              <button
                type="button"
                class="btn btn-outline-primary dropdown-toggle"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
               >
              {{ trans('cruds.order.payment_status') }}
              </button>
              <div class="dropdown-menu keep-open p-1">
                  @foreach(App\Models\Order::PAYMENT_STATUS as $key => $item)
                  <div class="custom-control custom-checkbox m-25">
                    <input type="checkbox" id="customCheckbox_{{$key}}" name="paymentStatus" class="custom-control-input payment-status-checkbox" value="{{$key}}" data-column="5">
                    <label class="custom-control-label mt-0" for="customCheckbox_{{$key}}">{{$item}}</label>
                  </div>
                  @endforeach
                  <a class="dropdown-item pointer p-0 mt-1 small clear-payment-status-checkbox">{{ trans('global.clear') }}</a>
              </div>
            </div>
         </div>
      </div>
      <!-- <div id="advanceFilter2" class="d-none">
          <tr>
            <td colspan="12">
              <div class="btn-group btn-group-toggle">
                <label class="btn btn-outline-primary waves-effect">
                  <input type="radio" class="advance-filter" name="advance_filter" id="advance_filter2" data-column="6" value="unfulfilled"> Unfulfilled
                </label>
                <div class="btn-group m-0">
                    <button type="button" class="btn btn-outline-secondary waves-effect">Secondary</button>
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split waves-effect" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" style="">
                      <a class="dropdown-item" href="javascript:void(0);">Option 1</a>
                      <a class="dropdown-item" href="javascript:void(0);">Option 2</a>
                      <a class="dropdown-item" href="javascript:void(0);">Option 3</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="javascript:void(0);">Separated link</a>
                    </div>
                </div>
             </div>
            </th>
          </tr>
      </div> -->
      <div class="card card-datatable">
        <table class="dt-column-search table table-responsive">
          <thead>
            <tr>
              <th>{{ trans('global.id') }}</th>
              <th>{{ trans('cruds.order.title_singular') }}</th>
              <th class="text-nowrap">{{ trans('cruds.order.fields.order_date') }}</th>
              <th>{{ trans('cruds.order.customer') }}</th>
              <th> {{ trans('cruds.order.total') }} </th>
              <th>{{ trans('cruds.order.payment') }}</th>
              <th>Status</th>
              <th>{{ trans('cruds.order.approved') }}</th>
              <th>{{ trans('cruds.order.method') }}</th>
              <th>{{ trans('cruds.order.fulfillment') }}</th>
              <th></th>
              <th>{{ trans('global.actions') }}</th>
            </tr>
            <tr class="bg-gradient-secondary">
              <td class="dtfc-fixed-left position-sticky search-bg-color" style="width: 18px; left: 0px;"></td>
              <td class="dtfc-fixed-left position-sticky search-bg-color" style="width: 49px; left: 59.5px;">
                <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.order.title_singular') }}">
              </td>
              <td>
                <input type="text"  class="form-control flatpickr-basic search"  placeholder="{{ trans('global.search') }}  {{ trans('cruds.order.fields.order_date') }}" />
              </td>
              <td>
                <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.order.customer') }}">
              </td>
              <td>
                <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.order.total') }}">
              </td>
              <td>
                  <select class="form-control-sm search" strict="true">
                      <option value>{{ trans('global.all') }}</option>
                      @foreach(App\Models\Order::PAYMENT_STATUS as $key => $item)
                          <option value="{{ $key }}">{{ $item }}</option>
                      @endforeach
                  </select>
              </td>
              <td></td>
              <td>
                <select class="form-control-sm search">
                    <option value>{{ trans('global.all') }}</option>
                    <option value="1">{{ trans('global.approved') }}</option>
                    <option value="0">{{ trans('global.not_approved') }}</option>
                </select>
              </td>
               <td>
                 <select class="form-control-sm search" strict="true">
                      <option value>{{ trans('global.all') }}</option>
                      @foreach($paymentMethods as $key => $item)
                          <option value="{{ $item->id }}">{{ $item->title }}</option>
                      @endforeach
                  </select>
              </td>
              <td></td>
              <td></td>
              <td class="dtfc-fixed-right position-sticky search-bg-color" style="width: 94px; right: 0px;"></td>
            </tr>
          </thead>
      </table>
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
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.fixedColumns.min.js')) }}"></script>
<script type="text/javascript">
  let createUrl = '{{ route('admin.orders.create') }}';
  let getListingUrl = "{{ route('admin.orders.index') }}";
  let refundOrderUrl = "{{ url('admin/refundorder') }}";
  let storeUrl = "{{ route('admin.orders.store') }}";
  let deleteUrl = "{{ route('admin.orders.massDestroy') }}";
</script>
<script src="{{ asset(mix('adminassets/js/order/order.min.js')) }}"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('.flatpickr-basic').flatpickr();
    jQuery(document).on('click', '.dropdown-menu.keep-open',function (e) {
      e.stopPropagation();
    });
  });
</script>
@endsection