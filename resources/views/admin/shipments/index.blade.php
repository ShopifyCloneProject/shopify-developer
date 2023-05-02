@extends('layouts/contentLayoutMaster')

@section('vendor-style')
   @include('admin/partials/datatableCss')
@endsection
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
@endsection

@section('content')
{{-- @can('shipment_status_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.shipmentstatus.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.shipmentstatus.title_singular') }}
            </a>
        </div>
    </div>
@endcan --}}

@can('shipment_status_create')
<input type="hidden" id="shipment_status_create" value="1">
@endcan
@can('shipment_status_edit')
<input type="hidden" id="shipment_status_edit" value="1">
@endcan
@can('shipment_status_delete')
<input type="hidden" id="shipment_status_delete" value="1">
@endcan
@can('shipment_status_export')
<input type="hidden" id="shipment_status_export" value="1">
@endcan

<section id="column-search-datatable">
  @can('shipment_status_access')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header border-bottom">
          <h4 class="card-title"> {{ trans('cruds.shipmentstatus.title') }}</h4>
        </div>
        <div class="card-datatable">
          <table class="dt-column-search table">
            <thead>
              <tr>
                  <th></th>
                  <th>{{ trans('cruds.shipmentstatus.fields.id') }}</th>
                  <th>{{ trans('cruds.shipmentstatus.fields.description') }}</th>
                  <th>{{ trans('cruds.shipmentstatus.fields.status_code') }}</th>
                  <th>{{ trans('cruds.shipmentstatus.fields.status') }}</th>
                  <th>{{ trans('cruds.shipmentstatus.fields.shipping_method') }}</th>
                  <th>{{ trans('global.actions') }}</th>
              </tr>
              <tr class="bg-gradient-secondary">
                    <td>
                    </td>
                    <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.shipmentstatus.fields.id') }}">
                    </td>
                    <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.shipmentstatus.fields.description') }}">
                    </td>
                    <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.shipmentstatus.fields.status_code') }}">
                    </td>
                     <td>
                         <select class="form-control-sm search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\ShipmentStatus::STATUS_RADIO as $key => $label)
                              <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="form-control-sm search">
                            <option value>{{ trans('global.all') }}</option>
                              @foreach($shipping_methods as $key => $item)
                                <option value="{{ $item->title }}">{{ $item->title }}</option>
                              @endforeach
                        </select>
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
  <!-- Modal to add new record -->
  <div class="modal modal-slide-in fade" id="modals-slide-in">
    <div class="modal-dialog sidebar-sm">
      <form class="add-new-record modal-content pt-0">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
        <div class="modal-header mb-1">
          <h5 class="modal-title" id="exampleModalLabel">{{ trans('global.add') }} {{ trans('cruds.shipmentstatus.title') }}</h5>
        </div>
        <div class="modal-body flex-grow-1">
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-fullname">{{ trans('cruds.shipmentstatus.fields.description') }}</label>
            <input
              type="text"
              class="form-control dt-full-name"
              id="addDescription"
              required
            />
          </div>
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-post">{{ trans('cruds.shipmentstatus.fields.status_code') }}</label>
            <input
              type="text"
              id="addStatusCode"
              class="form-control dt-post"
              required
            />
          </div>
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-status">{{ trans('cruds.shipmentstatus.fields.status') }}</label>
            <select class="form-control" name="status" id="addStatus">
               @foreach(App\Models\ShipmentStatus::STATUS_RADIO as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
               @endforeach
             </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-post">{{ trans('cruds.shipmentstatus.fields.shipping_method') }}</label>
            <select class="form-control" name="shipping_method" id="shipping_method_id">
               @foreach($shipping_methods as $shipping)
                <option value="{{ $shipping->id }}">{{ $shipping->title }}</option>
               @endforeach
            </select>
          </div>
          <button type="button" class="btn btn-primary data-submit mr-1">{{ trans('global.submit') }}</button>
          <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">{{ trans('global.cancel') }}</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Modals start -->
  <div class="modal fade text-left" id="default" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form class="add-new-record modal-content pt-0">
          <input type="hidden" id="shipmentStatusId">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel1">{{ trans('global.edit') }} {{ trans('cruds.shipmentstatus.title') }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-fullname">{{ trans('cruds.shipmentstatus.fields.description') }}</label>
                <input type="text" class="form-control" id="editDescription" required/>
              </div>
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-post">{{ trans('cruds.shipmentstatus.fields.status_code') }}</label>
                <input type="text" class="form-control" id="editStatusCode" required/>
              </div>
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-post">{{ trans('cruds.shipmentstatus.fields.status') }}</label>
                 <select class="form-control" name="status" id="editStatus">
                   @foreach(App\Models\ShipmentStatus::STATUS_RADIO as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                   @endforeach
                 </select>
              </div>
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-post">{{ trans('cruds.shipmentstatus.fields.shipping_method') }}</label>
                <select class="form-control select2" name="shipping_method" id="shippingMethodId">
                  @foreach($shipping_methods as $shipping)
                    <option value="{{ $shipping->id }}">{{ $shipping->title }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary data-edit">{{ trans('global.edit') }}</button>
              <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ trans('global.cancel') }}</button>
            </div>
          </div>
        </form>
    </div>
  </div>
  <!-- Edit Modals end -->
</section>
@endsection

@section('vendor-script')
  @include('admin/partials/datatableJs')
@endsection

@section('page-script')
    <script type="text/javascript">
       let getListingUrl = "{{ route('admin.shipments.index') }}";
       let storeUrl = "{{ route('admin.shipments.store') }}";
       let deleteUrl = "{{ route('admin.shipments.massDestroy') }}";
       
    </script>
    <script src="{{ asset(mix('adminassets/js/shipments/shipments.min.js')) }}"></script>
@endsection