@extends('layouts/contentLayoutMaster')
@section('title', 'Files Settings')

@section('vendor-style')
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
 
@endsection

@can('files_access_create')
@section('content')
<section id="filesSettingsApp">
    <filessettings :data="{{ json_encode($data) }}"></filessettings>
</section>
@endsection
@endcan

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <!-- <script src="{{ asset(mix('adminassets/js/collection/create.min.js')) }}"></script> -->
  <script src="{{ asset('js/admin/filessettings/app.js') }}"></script>
@endsection
