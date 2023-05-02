@extends('layouts/contentLayoutMaster')
@section('title', 'Locations')

@section('vendor-style')
@endsection
@section('page-style')
@endsection

@section('content')
@can('address_create')
    <input type="hidden" id="address_create" value="1">
@endcan
  
@can('address_access')
<section id="locationSettingsApp">
    <locationsettings :list="{{ json_encode($list) }}" :data="{{ json_encode($data) }}" :globalsettings="{{ json_encode($globalSettings) }}"></locationsettings>
</section>
@endcan

@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <!-- <script src="{{ asset(mix('adminassets/js/collection/create.min.js')) }}"></script> -->
  <script type="text/javascript">
  	let createUrl = "{{ url('admin/settings/locations/create') }}";
  	let locationUrl = "{{ url('admin/settings/locations') }}";
  </script>
  <script src="{{ asset('js/admin/location/app.js') }}"></script>
@endsection
