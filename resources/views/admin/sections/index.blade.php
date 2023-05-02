@extends('layouts/contentLayoutMaster')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/rowGroup.bootstrap4.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
@endsection

@section('content')

<section id="column-search-datatable">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header border-bottom">
          <h4 class="card-title">{{ trans('cruds.section.title') }}</h4>
        </div>
        <div class="card-datatable">
          <table class="dt-column-search table">
            <thead>
              <tr>
                  <th></th>
                  <th>{{ trans('global.id') }}</th>
                  <th>{{ trans('cruds.section.fields.columnname') }}</th>
                  <th>{{ trans('cruds.section.fields.displaycolumnname') }}</th>
                  <th>{{ trans('global.status') }}</th>
                  <th>{{ trans('global.actions') }}</th>
              </tr>
               <tr class="bg-gradient-secondary">
                  <td></td>
                  <td>
                      <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('global.id') }}">
                  </td>
                   <td>
                      <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.section.fields.columnname') }}">
                  </td>
                  <td>
                      <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.section.fields.displaycolumnname') }}">
                  </td>
                  <td>
                    <select class="form-control-sm search">
                          <option value>{{ trans('global.all') }}</option>
                          @foreach(App\Models\section::STATUS_RADIO as $key => $item)
                              <option value="{{ $key }}">{{ $item }}</option>
                          @endforeach
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

  <!-- Modal to add new record -->
  <div class="modal modal-slide-in fade" id="modals-slide-in">
    <div class="modal-dialog sidebar-sm">
      <form class="add-new-record modal-content pt-0">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
        <div class="modal-header mb-1">
          <h5 class="modal-title" id="exampleModalLabel">{{ trans('global.add') }} {{ trans('cruds.section.title') }}</h5>
        </div>
        <div class="modal-body flex-grow-1">
           <div class="form-group">
            <label class="form-label" for="basic-icon-default-fullname">{{ trans('cruds.section.fields.columnname') }}</label>
            <input
              type="text"
              class="form-control dt-full-name"
              id="addsectionName"
            />
          </div>
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-fullname">{{ trans('cruds.section.fields.displaycolumnname') }}</label>
            <input
              type="text"
              class="form-control dt-full-name"
              id="addsection"
            />
          </div>
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-post">{{ trans('global.status') }}</label>
            <select class="form-control" name="status" id="addStatus">
               @foreach(App\Models\Section::STATUS_RADIO as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
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
          <input type="hidden" id="sectionId">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel1">{{ trans('global.edit') }} {{ trans('cruds.section.title') }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-fullname">{{ trans('cruds.section.fields.columnname') }}</label>
                <input type="text"  class="form-control dt-full-name"  id="editsectionName"  />
              </div>
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-fullname">{{ trans('cruds.section.fields.displaycolumnname') }}</label>
                <input type="text" class="form-control" id="editsection"/>
              </div>
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-post">{{ trans('global.status') }}</label>
                 <select class="form-control" name="status" id="editStatus">
                   @foreach(App\Models\Section::STATUS_RADIO as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
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
       let getListingUrl = "{{ route('admin.sections.index') }}";
       let storeUrl = "{{ route('admin.sections.store') }}";
       let deleteUrl = "{{ route('admin.sections.massDestroy') }}";
   </script>
   <script src="{{ asset(mix('adminassets/js/section/section.min.js')) }}"></script>
@endsection
