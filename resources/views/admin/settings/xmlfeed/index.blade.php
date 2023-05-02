@extends('layouts/contentLayoutMaster')

@section('vendor-style')
   @include('admin/partials/datatableCss')
@endsection
@section('page-style')
@endsection

@section('title')
{{ trans('cruds.xmlfeed.title_singular') }}
@endsection

@can('xmlfeed_create')
<input type="hidden" id="xmlfeed_create" value="1">
@endcan
@can('xmlfeed_export_access')
<input type="hidden" id="xmlfeed_export_access" value="1">
@endcan
@can('xmlfeed_edit')
<input type="hidden" id="xmlfeed_edit" value="1">
@endcan
@can('xmlfeed_delete')
<input type="hidden" id="xmlfeed_delete" value="1">
@endcan

@section('content')
<section id="column-search-datatable">
@can('xmlfeed_access_create')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-datatable">
          <table class="dt-column-search table">
            <thead>
              <tr >
                  <th></th>
                  <th>{{ trans('cruds.xmlfeed.id') }}</th>
                  <th>{{ trans('cruds.xmlfeed.title') }}</th>
                  <th>{{ trans('cruds.xmlfeed.url') }}</th>
                  <th></th>
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
       let getListingUrl = "{{ url('admin/settings/xmlfeed') }}";
       let storeUrl = "{{ url('admin/settings/xmlfeed') }}";
       let deleteUrl = "{{ url('admin/settings/xmlfeed/destroy') }}";
       var createUrl = '{{ route('admin.settings.xmlfeed.create') }}';
   </script>
   <script src="{{ asset(mix('adminassets/js/xmlfeed/xmlfeed.min.js')) }}"></script>
@endsection