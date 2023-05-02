@extends('layouts/contentLayoutMaster')

@section('vendor-style')
   @include('admin/partials/datatableCss')
@endsection
@section('page-style')

@endsection

@section('title')
  {{ trans('cruds.fbpixel.title_singular') }}
@endsection

@can('fbpixel_create')
<input type="hidden" id="fbpixel_create" value="1">
@endcan
@can('fbpixel_edit')
<input type="hidden" id="fbpixel_edit" value="1">
@endcan
@can('fbpixel_delete')
<input type="hidden" id="fbpixel_delete" value="1">
@endcan
@can('fbpixel_export_access')
<input type="hidden" id="fbpixel_export_access" value="1">
@endcan

@section('content')
<section id="column-search-datatable"> 
@can('fbpixel_access_create')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header border-bottom">
          <h4 class="card-title">{{ trans('cruds.fbpixel.title') }}</h4>
        </div>
          <div class="card-datatable">
            <table class="dt-column-search table">
              <thead>
                <tr>
                    <th></th>
                    <th>{{ trans('global.id') }}</th>
                    <th>{{ trans('cruds.fbpixel.title') }}</th>
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
                             @foreach(App\Models\Fbpixel::STATUS_SELECT as $key => $label)
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
          <h5 class="modal-title" id="exampleModalLabel">{{ trans('global.add') }} {{ trans('cruds.fbpixel.title') }}</h5>
        </div>
        <div class="modal-body flex-grow-1">
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-fullname">{{ trans('cruds.fbpixel.title') }}</label>
            <input
              type="text"
              class="form-control dt-full-name"
              id="addName"
            />
          </div>
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-post">{{ trans('global.status') }}</label>
            <select class="form-control" name="status" id="addStatus">
               @foreach(App\Models\fbpixel::STATUS_SELECT as $key => $label)
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
              <h4 class="modal-title" id="myModalLabel1">{{ trans('global.edit') }} {{ trans('cruds.fbpixel.title') }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-fullname">{{ trans('cruds.fbpixel.title') }}</label>
                <input type="text" class="form-control" id="editName"/>
              </div>
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-post">{{ trans('global.status') }}</label>
                 <select class="form-control" name="status" id="editStatus">
                   @foreach(App\Models\fbpixel::STATUS_SELECT as $key => $label)
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
       let getListingUrl = "{{ route('admin.fbpixels.index') }}";
       let storeUrl = "{{ route('admin.fbpixels.store') }}";
       let deleteUrl = "{{ route('admin.fbpixels.massDestroy') }}";
   </script>
   <script src="{{ asset(mix('adminassets/js/fbpixel/fbpixel.min.js')) }}"></script>
@endsection