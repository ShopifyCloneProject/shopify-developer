@extends('layouts/contentLayoutMaster')
@section('title', 'themes')
@section('vendor-style')
@endsection
@section('page-style')
@endsection

@section('content')
@can('select_theme_create')
<section id="themeSelectionApp">
    <themeselection :list="{{ json_encode($list) }}"></themeselection>
</section>
@endcan
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <!-- <script src="{{ asset(mix('adminassets/js/themes/create.min.js')) }}"></script> -->
  
  <script src="{{ asset('js/admin/themes/app.js') }}"></script>
@endsection
