@extends('layouts/contentLayoutMaster')

@section('vendor-style')
   @include('admin/partials/datatableCss')
   <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection

@section('title')
  {{ trans('cruds.paymentMethod.title_singular') }}
@endsection

@section('content')
<!-- @can('payment_method_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.payment-methods.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.paymentMethod.title_singular') }}
            </a>
        </div>
    </div>
@endcan -->

@can('payment_method_create')
<input type="hidden" id="payment_method_create" value="1">
@endcan
@can('payment_method_edit')
<input type="hidden" id="payment_method_edit" value="1">
@endcan
@can('payment_method_delete')
<input type="hidden" id="payment_method_delete" value="1">
@endcan
@can('payment_method_export_access')
<input type="hidden" id="payment_method_export_access" value="1">
@endcan

<section id="column-search-datatable">
  @can('payment_method_access')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header border-bottom">
          <h4 class="card-title">{{ trans('cruds.paymentMethod.title') }}</h4>
        </div>
          <div class="card-datatable">
            <table class="dt-column-search table">
              <thead>
                <tr>
                    <th></th>
                    <th>{{ trans('global.id') }}</th>
                    <th>{{ trans('global.title') }}</th>
                    <th>{{ trans('global.status') }}</th>
                    <th>Types</th>
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
                             @foreach(App\Models\PaymentMethod::STATUS_RADIO as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                              @endforeach
                          </select>
                      </td>
                      <td>
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
      <form class="add-new-record modal-content pt-0" id="frmAddPaymentMethod">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
        <div class="modal-header mb-1">
          <h5 class="modal-title" id="exampleModalLabel">{{ trans('global.add') }} {{ trans('cruds.paymentMethod.title') }}</h5>
        </div>
        <div class="modal-body flex-grow-1">
          <div class="form-group">
            <label class="form-label" for="addTitle">{{ trans('global.title') }}</label>
            <input
              type="text"
              class="form-control dt-full-name"
              id="addTitle"
              name="addTitle"
            />
          </div>
          <div class="form-group">
              <label class="form-label" for="basic-icon-default-post">{{ trans('global.types') }}</label>
              <select class="form-control select2" name="types[]" id="types" multiple>
                  @foreach($paymentTypes as $key => $types)
                      <option value="{{ $types->id }}">{{ $types->name }}</option>
                  @endforeach
              </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-post">{{ trans('global.status') }}</label>
            <select class="form-control" name="status" id="addStatus">
               @foreach(App\Models\PaymentMethod::STATUS_RADIO as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
               @endforeach
             </select>
          </div>
          <button type="submit" class="btn btn-primary data-submit mr-1" id="submitdata">{{ trans('global.submit') }}</button>
          <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">{{ trans('global.cancel') }}</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Modals start -->
  <div class="modal fade text-left" id="default" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form class="add-new-record modal-content pt-0" id="frmEditPaymentMethod">
          <input type="hidden" id="paymentId">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel1">{{ trans('global.edit') }} {{ trans('cruds.paymentMethod.title') }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-fullname">{{ trans('global.title') }}</label>
                <input type="text" class="form-control" id="editTitle"/>
              </div>
              <div class="form-group">
                  <label class="form-label" for="basic-icon-default-post">{{ trans('global.types') }}</label>
                  <select class="form-control select2" name="types[]" id="editTypes" multiple>
                      @foreach($paymentTypes as $key => $types)
                          <option value="{{ $types->id }}">{{ $types->name }}</option>
                      @endforeach
                  </select>
              </div>
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-post">{{ trans('global.status') }}</label>
                 <select class="form-control" name="status" id="editStatus">
                   @foreach(App\Models\PaymentMethod::STATUS_RADIO as $key => $label)
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
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection

@section('page-script')
   <script type="text/javascript">
       let getListingUrl = "{{ route('admin.payment-methods.index') }}";
       let storeUrl = "{{ route('admin.payment-methods.store') }}";
       let deleteUrl = "{{ route('admin.payment-methods.massDestroy') }}";
   </script>
   <script type="text/javascript">
        var jqForm = $('#frmAddPaymentMethod');
        // var select = $('.select2');
        // select.each(function () {
        //   var $this = $(this);
        //   $this.wrap('<div class="position-relative"></div>');
        //   $this
        //     .select2({
        //       placeholder: 'Select value',
        //       dropdownParent: $this.parent()
        //     })
        //     .change(function () {
        //        valid = true;
        //     });
        // });

        if (jqForm.length) {
            jqForm.validate({
                  rules: {
                    'addTitle': {
                      required: true
                    }, 
                    'types': {
                      required: true
                    },
                  }
            });
        }
   </script>
   <script src="{{ asset(mix('adminassets/js/paymentmethod/paymentmethod.min.js')) }}"></script>
@endsection