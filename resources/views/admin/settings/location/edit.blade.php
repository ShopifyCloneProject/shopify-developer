@extends('layouts/contentLayoutMaster')
@section('title', 'Edit Locations')

@section('vendor-style')
@endsection
@section('page-style')
@endsection

@section('content')
<section id="locationSettingsApp">
    <createlocation :list="{{ json_encode($list) }}" :data="{{ json_encode($data) }}" type="edit" :globalsettings="{{ json_encode($globalSettings) }}"></createlocation>
</section>
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <!-- <script src="{{ asset(mix('adminassets/js/collection/create.min.js')) }}"></script> -->
  <script type="text/javascript">
  	let locationUrl = "{{ url('admin/settings/locations') }}";
  </script>
  <script src="{{ asset('js/admin/location/app.js') }}"></script>
@endsection
