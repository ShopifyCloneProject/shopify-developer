@extends('layouts/contentLayoutMaster')

@section('title', 'Collection')

@section('vendor-style')
   @include('admin/partials/datatableCss')
@endsection
@section('page-style')
   <style>
      .conditionx-list {
          font-size: 12px;
          line-height: 20px;
      }
   </style>
@endsection

@section('content')
   <!--  @can('collection_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.collections.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.collection.title_singular') }}
                </a>
            </div>
        </div>
    @endcan -->
  @can('collection_create')
    <input type="hidden" id="collection_create" value="1">
  @endcan
  @can('collection_edit')
  <input type="hidden" id="collection_edit" value="1">
  @endcan
  @can('collection_delete')
  <input type="hidden" id="collection_delete" value="1">
  @endcan
  @can('collection_export')
  <input type="hidden" id="collection_export" value="1">
  @endcan


    <section id="column-search-datatable">
      @can('collection_access')
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-datatable">
              <table class="dt-column-search table">
                <thead>
                  <tr>
                    <th></th>
                    <th></th>
                    <th>{{ trans('cruds.collection.title') }}</th>
                    <th>Product conditions</th>
                    <th></th>
                  </tr>
                  <tr class="bg-gradient-secondary">
                      <td></td>
                      <td>
                      </td>
                      <td>  <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.collection.title_singular') }}"></td>
                      <td></td>
                      <td></td>
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
     let getListingUrl = "{{ route('admin.collections.index') }}";
     let storeUrl = "{{ route('admin.collections.store') }}";
     let deleteUrl = "{{ route('admin.collections.massDestroy') }}";
     let createUrl = "{{ route('admin.collections.create') }}";
  </script>
  <script src="{{ asset(mix('adminassets/js/collection/index.min.js')) }}"></script>
@endsection