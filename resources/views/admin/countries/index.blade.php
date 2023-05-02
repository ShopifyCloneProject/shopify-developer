@extends('layouts/contentLayoutMaster')

@section('vendor-style')
   @include('admin/partials/datatableCss')
@endsection
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
@endsection

@section('content')
{{-- @can('country_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.country.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.country.title_singular') }}
            </a>
        </div>
    </div>
@endcan --}}

@can('country_create')
<input type="hidden" id="country_create" value="1">
@endcan
@can('country_edit')
<input type="hidden" id="country_edit" value="1">
@endcan
@can('country_delete')
<input type="hidden" id="country_delete" value="1">
@endcan
@can('country_export_access')
<input type="hidden" id="country_export_access" value="1">
@endcan

<section id="column-search-datatable">
  @can('country_access')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header border-bottom">
          <h4 class="card-title"> {{ trans('cruds.country.title') }}</h4>
        </div>
        <div class="card-datatable">
          <table class="dt-column-search table">
            <thead>
              <tr>
                  <th></th>
                  <th>{{ trans('cruds.country.fields.id') }}</th>
                  <th>{{ trans('cruds.country.fields.name') }}</th>
                  <th>{{ trans('cruds.country.fields.short_code') }}</th>
                  <th>{{ trans('cruds.country.fields.phone_code') }}</th>
                  <th>{{ trans('global.actions') }}</th>
              </tr>
              <tr class="bg-gradient-secondary">
                    <td>
                    </td>
                    <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.state.fields.id') }}">
                    </td>
                    <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.country.fields.name') }}">
                    </td>
                    <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.country.fields.short_code') }}">
                    </td>
                     <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.country.fields.phone_code') }}">
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
          <h5 class="modal-title" id="exampleModalLabel">{{ trans('global.add') }} {{ trans('cruds.country.fields.name') }}</h5>
        </div>
        <div class="modal-body flex-grow-1">
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-fullname">{{ trans('cruds.country.fields.name') }}</label>
            <input
              type="text"
              class="form-control dt-full-name"
              id="addCName"
              required
            />
          </div>
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-post">{{ trans('cruds.country.fields.short_code') }}</label>
            <input
              type="text"
              id="addShortCode"
              class="form-control dt-post"
              required
            />
          </div>
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-email">{{ trans('cruds.country.fields.phone_code') }}</label>
            <input
              type="text"
              id="addPhoneCode"
              class="form-control dt-email"
              required
            />
            <small class="form-text text-muted"> You can use letters, numbers & periods </small>
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
          <input type="hidden" id="countryId">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel1">{{ trans('global.edit') }} {{ trans('cruds.country.fields.name') }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-fullname">{{ trans('cruds.country.fields.name') }}</label>
                <input type="text" class="form-control" id="editCName" required/>
              </div>
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-post">{{ trans('cruds.country.fields.short_code') }}</label>
                <input type="text" class="form-control" id="editShortCode" required/>
              </div>
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-post">{{ trans('cruds.country.fields.phone_code') }}</label>
                <input type="text" class="form-control" id="editPhoneCode" required/>
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
       let getListingUrl = "{{ route('admin.countries.index') }}";
       let storeUrl = "{{ route('admin.countries.store') }}";
       let deleteUrl = "{{ route('admin.countries.massDestroy') }}";
       
    </script>
    <script src="{{ asset(mix('adminassets/js/countries/countries.min.js')) }}"></script>
@endsection