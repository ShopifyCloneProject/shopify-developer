@extends('layouts/contentLayoutMaster')
@section('title', 'Theme Settings')

@section('vendor-style')
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection
@section('page-style')
 
@endsection

@section('content')
@can('theme_settings_create')
<section id="themeSettingsApp">
    {{-- <themesettings></themesettings> --}}
    <themesettings :data="{{ json_encode($data) }}" ></themesettings>
</section>
@endcan
@endsection

@section('vendor-script')
 <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <!-- vendor files -->
@endsection

@section('page-script')
  <!-- <script src="{{ asset(mix('adminassets/js/collection/create.min.js')) }}"></script> -->
  <script src="{{ asset('js/admin/themesettings/app.js') }}"></script>
@endsection
