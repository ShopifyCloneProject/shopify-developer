@extends('layouts/contentLayoutMaster')
@section('title', 'General Settings')

@section('vendor-style')
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
 
@endsection

@section('content')
@can('user_store_create')
<section id="generalSettingsApp">
    <generalsettings :list="{{ json_encode($list) }}" :data="{{ json_encode($data) }}" ></generalsettings>
</section>
@endcan
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <!-- <script src="{{ asset(mix('adminassets/js/collection/create.min.js')) }}"></script> -->
  <script src="{{ asset('js/admin/generalsettings/app.js') }}"></script>
@endsection
