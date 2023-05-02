@extends('layouts/contentLayoutMaster')
@section('title', 'languages')
@section('vendor-style')
@endsection
@section('page-style')
@endsection

@section('content')
@can('languages_selection_create')
<section id="languageSettingsApp">
    <languagesettings :list="{{ json_encode($list) }}"></languagesettings>
</section>
@endcan
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <!-- <script src="{{ asset(mix('adminassets/js/themes/create.min.js')) }}"></script> -->
  
  <script src="{{ asset('js/admin/languagesettings/app.js') }}"></script>
@endsection
