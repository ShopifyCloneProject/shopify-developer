@extends('layouts/contentLayoutMaster')
@section('title', 'Billing Settings')

@section('vendor-style')
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
 
@endsection

@section('content')
@can('billing_access_create')
<section id="billingSettingsApp">
    <billingsettings :data="{{ json_encode($data) }}"></billingsettings>
</section>
@endcan
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <!-- <script src="{{ asset(mix('adminassets/js/collection/create.min.js')) }}"></script> -->
  <script src="{{ asset('js/admin/billingsettings/app.js') }}"></script>
@endsection
