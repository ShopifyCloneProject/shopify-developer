@extends('layouts/contentLayoutMaster')
@section('title', 'Legal Settings')

@section('vendor-style')
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
 
@endsection

@section('content')
@can('legal_policy_access_create')
<section id="legalSettingsApp">
    <legalsettings :data="{{ json_encode($data) }}"></legalsettings>
</section>
@endcan
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <!-- <script src="{{ asset(mix('adminassets/js/collection/create.min.js')) }}"></script> -->
  <script src="{{ asset('js/admin/legalsettings/app.js') }}"></script>
@endsection
