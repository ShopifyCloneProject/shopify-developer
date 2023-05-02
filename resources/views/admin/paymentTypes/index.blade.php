@extends('layouts/contentLayoutMaster')

@section('vendor-style')
   @include('admin/partials/datatableCss')
@endsection
@section('page-style')

@endsection

@section('title')
  {{ trans('cruds.paymentType.title_singular') }}
@endsection

@section('content')

@can('payment_type_create')
<input type="hidden" id="payment_type_create" value="1">
@endcan
@can('payment_type_edit')
<input type="hidden" id="payment_type_edit" value="1">
@endcan
@can('payment_type_delete')
<input type="hidden" id="payment_type_delete" value="1">
@endcan
@can('payment_type_export_access')
<input type="hidden" id="payment_type_export_access" value="1">
@endcan

<section id="column-search-datatable">
  @can('payment_type_access')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header border-bottom">
          <h4 class="card-title">{{ trans('cruds.paymentType.title') }}</h4>
        </div>
          <div class="card-datatable">
            <table class="dt-column-search table">
              <thead>
                <tr>
                    <th></th>
                    <th>{{ trans('global.id') }}</th>
                    <th>{{ trans('global.name') }}</th>
                    <th>{{ trans('global.status') }}</th>
                    <th>{{ trans('global.actions') }}</th>
                </tr>
                <tr class="bg-gradient-secondary">
                      <td>
                      </td>
                      <td>
                      </td>
                      <td>
                          <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('global.title') }}">
                      </td>
                      <td>
                          <select class="form-control-sm search">
                            <option value>{{ trans('global.all') }}</option>
                             @foreach(App\Models\PaymentType::STATUS_RADIO as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
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
          <h5 class="modal-title" id="exampleModalLabel">{{ trans('global.add') }} {{ trans('cruds.paymentType.title') }}</h5>
        </div>
        <div class="modal-body flex-grow-1">
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-fullname">{{ trans('global.title') }}</label>
            <input
              type="text"
              class="form-control dt-full-name"
              id="addName"
            />
          </div>
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-post">{{ trans('global.status') }}</label>
            <select class="form-control" name="status" id="addStatus">
               @foreach(App\Models\paymentType::STATUS_RADIO as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
               @endforeach
             </select>
          </div>
          <button type="button" class="btn btn-primary data-submit mr-1" id="submitdata">{{ trans('global.submit') }}</button>
          <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">{{ trans('global.cancel') }}</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Modals start -->
  <div class="modal fade text-left" id="default" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form class="add-new-record modal-content pt-0">
          <input type="hidden" id="paymentId">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel1">{{ trans('global.edit') }} {{ trans('cruds.paymentType.title') }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-fullname">{{ trans('global.title') }}</label>
                <input type="text" class="form-control" id="editName"/>
              </div>
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-post">{{ trans('global.status') }}</label>
                 <select class="form-control" name="status" id="editStatus">
                   @foreach(App\Models\paymentType::STATUS_RADIO as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                   @endforeach
                 </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary data-edit" id="editdata">{{ trans('global.edit') }}</button>
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
       let getListingUrl = "{{ route('admin.payment-types.index') }}";
       let storeUrl = "{{ route('admin.payment-types.store') }}";
       let deleteUrl = "{{ route('admin.payment-types.massDestroy') }}";
   </script>
   <script src="{{ asset(mix('adminassets/js/paymenttype/paymenttype.min.js')) }}"></script>
@endsection