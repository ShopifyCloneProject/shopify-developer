@extends('layouts/contentLayoutMaster')

@section('vendor-style')
   @include('admin/partials/datatableCss')
@endsection
@section('page-style')
@endsection

@section('title')
{{ trans('cruds.state.title_singular') }}
@endsection


@section('content')
{{-- @can('state_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.products.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.state.title_singular') }}
            </a>
        </div>
    </div>
@endcan --}}

@can('state_create')
<input type="hidden" id="state_create" value="1">
@endcan
@can('state_edit')
<input type="hidden" id="state_edit" value="1">
@endcan
@can('state_delete')
<input type="hidden" id="state_delete" value="1">
@endcan
@can('state_export_access')
<input type="hidden" id="state_export_access" value="1">
@endcan

<section id="column-search-datatable">
  @can('state_access')
  <div class="row">
    <div class="col-12">
      <div class="card">
       {{--  <div class="card-header border-bottom">
          <h4 class="card-title">{{ trans('cruds.state.title') }}</h4>
        </div> --}}
        <div class="card-datatable">
          <table class="dt-column-search table">
            <thead>
              <tr >
                  <th></th>
                  <th>{{ trans('cruds.state.fields.id') }}</th>
                  <th>{{ trans('cruds.state.title_singular') }} {{ trans('cruds.state.fields.name') }}</th>
                  <th>{{ trans('cruds.state.fields.country') }}</th>
                  <th>{{ trans('global.country') }}</th>
              </tr>
              <tr class="bg-gradient-secondary">
                    <td>
                    </td>
                    <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.state.fields.id') }}">
                    </td>
                    <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.state.title_singular') }} {{ trans('cruds.state.fields.name') }}">
                    </td>
                    <td>
                        <select class="form-control-sm search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($countries as $key => $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
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
          <h5 class="modal-title" id="exampleModalLabel">{{ trans('global.new') }} {{ trans('cruds.state.title_singular') }}</h5>
        </div>
        <div class="modal-body flex-grow-1">
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-fullname">{{ trans('cruds.state.fields.name') }}</label>
            <input
              type="text"
              class="form-control"
              id="dt-state-name"
              placeholder="{{ trans('global.enter') }} {{ trans('cruds.state.fields.name') }}"
              aria-label="{{ trans('global.enter') }} {{ trans('cruds.state.fields.name') }}"
            />
          </div>
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-post">{{ trans('cruds.state.fields.country') }}</label>
            <select class="form-control" name="country" id="dt-country-id">
               @foreach($countries as $country)
                <option value="{{ $country->id }}">{{ $country->name }}</option>
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
          <input type="hidden" id="stateId">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel1">{{ trans('global.edit') }} {{ trans('cruds.state.title_singular') }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-fullname">{{ trans('cruds.state.fields.name') }}</label>
                <input type="text" class="form-control" id="stateName"/>
              </div>
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-post">{{ trans('cruds.state.fields.country') }}</label>
                <select class="form-control select2" name="country" id="countryId">
                   @foreach($countries as $countrie)
                    <option value="{{ $countrie->id }}">{{ $countrie->name }}</option>
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
       let getListingUrl = "{{ route('admin.states.index') }}";
       let storeUrl = "{{ route('admin.states.store') }}";
       let deleteUrl = "{{ route('admin.states.massDestroy') }}";

   </script>
   <script src="{{ asset(mix('adminassets/js/state/state.min.js')) }}"></script>
@endsection