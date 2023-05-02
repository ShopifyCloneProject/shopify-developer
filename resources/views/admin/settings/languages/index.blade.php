@extends('layouts/contentLayoutMaster')
@section('title', 'themes')
@section('vendor-style')
@endsection
@section('page-style')
@endsection

@section('content')
<section id="themeSelectionApp">
    <themeselection :list="{{ json_encode($list) }}"></themeselection>
</section>
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <!-- <script src="{{ asset(mix('adminassets/js/themes/create.min.js')) }}"></script> -->
  <script type="text/javascript">
  	let createUrl = "{{ url('admin/settings/themes/create') }}";
  	let themeUrl = "{{ url('admin/settings/themes') }}";
  </script>
  <script src="{{ asset('js/admin/themes/app.js') }}"></script>
@endsection
