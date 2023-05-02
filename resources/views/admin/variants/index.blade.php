@extends('layouts/contentLayoutMaster')

@section('vendor-style')
   @include('admin/partials/datatableCss')
@endsection
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
@endsection

@section('title')
  {{ trans('cruds.variant.title_singular') }}
@endsection

@section('content')
<!-- @can('variant_type_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.variant-types.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.variant.title_singular') }}
            </a>
        </div>
    </div>
@endcan -->

<section id="column-search-datatable">
  <div class="row">
    <div class="col-12">
      <div class="card">
       {{--  <div class="card-header border-bottom">
          <h4 class="card-title">{{ trans('cruds.variant.title') }}</h4>
        </div> --}}
        <div class="card-datatable">
          <table class="dt-column-search table">
            <thead>
              <tr >
                <th></th>
                <th>
                  {{ trans('cruds.variant.fields.id') }}
                </th>
                <th>
                  {{ trans('cruds.variant.fields.title') }}
                </th>
                <th>
                  {{ trans('cruds.variant.fields.status') }}
                </th>
                <th></th>
              </tr>
              <tr class="bg-gradient-secondary">
                    <td>
                    </td>
                    <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.variant.fields.id') }}">
                    </td>
                    <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.variant.title_singular') }} {{ trans('cruds.variant.fields.title') }}">
                    </td>
                    <td>
                        <select class="form-control-sm search">
                            <option value>{{ trans('global.all') }}</option>
                             @foreach(App\Models\variant::STATUS_RADIO as $key => $label)
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

  <!-- Modal to add new record -->
  <div class="modal modal-slide-in fade" id="modals-slide-in">
    <div class="modal-dialog sidebar-sm">
      <form class="add-new-record modal-content pt-0">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
        <div class="modal-header mb-1">
          <h5 class="modal-title" id="exampleModalLabel">{{ trans('global.new') }} {{ trans('cruds.variant.title_singular') }}</h5>
        </div>
        <div class="modal-body flex-grow-1">
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-fullname">{{ trans('cruds.variant.fields.title') }}</label>
            <input
              type="text"
              class="form-control"
              id="addName"
            />
          </div>
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-post">{{ trans('cruds.variant.fields.status') }}</label>
            <select class="form-control" name="status" id="addStatus">
               @foreach(App\Models\Vendor::STATUS_RADIO as $key => $label)
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
          <input type="hidden" id="typeId">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel1">{{ trans('global.edit') }} {{ trans('cruds.variant.title_singular') }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-fullname">{{ trans('cruds.variant.fields.title') }}</label>
                <input type="text" class="form-control" id="editName"/>
              </div>
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-post">{{ trans('cruds.variant.fields.status') }}</label>
                <select class="form-control" name="status" id="editStatus">
                   @foreach(App\Models\Vendor::STATUS_RADIO as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary data-edit">{{ trans('global.submit') }}</button>
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
       let getListingUrl = "{{ route('admin.variant.index') }}";
       let storeUrl = "{{ route('admin.variant.store') }}";
       let deleteUrl = "{{ route('admin.variant.massDestroy') }}";

   </script>
   <script src="{{ asset(mix('adminassets/js/variant/variant.min.js')) }}"></script>
@endsection