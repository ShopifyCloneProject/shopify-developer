@extends('layouts/contentLayoutMaster')
@section('title', 'Default XMl Feed')

@section('vendor-style')
  <!-- vendor css files -->
  <link rel='stylesheet' href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('content')
@can('default_xmlfeed_access_create')
<section id="xmlfeedApp">
    <defaultxml :data="{{ json_encode($data) }}"></defaultxml>
</section>
@endcan
@endsection

@section('vendor-script')
  <!-- vendor files -->
  {{-- select2 min js --}}
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

@section('page-script')
  <script src="{{ asset(mix('js/admin/xmlfeed/app.js')) }}"></script>
@endsection
