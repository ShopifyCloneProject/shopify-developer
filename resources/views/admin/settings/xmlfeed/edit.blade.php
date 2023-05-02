@extends('layouts/contentLayoutMaster')
@section('title', 'XMl Feed')


@section('vendor-style')
  <!-- vendor css files -->
  <link rel='stylesheet' href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('content')
<section id="xmlfeedApp">
    <editxmlfeed :data="{{ json_encode($data) }}"></editxmlfeed>
</section>
@endsection

@section('vendor-script')
  <!-- vendor files -->
  {{-- select2 min js --}}
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

@section('page-script')
  <script src="{{ asset(mix('js/admin/xmlfeed/app.js')) }}"></script>
@endsection
