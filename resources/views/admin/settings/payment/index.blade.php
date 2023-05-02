@extends('layouts/contentLayoutMaster')
@section('title', 'Payment Settings')
@section('vendor-style')
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
 
@endsection

@section('content')
@can('payment_meethods_create')
    <input type="hidden" id="payment_meethods_create" value="1">
@endcan

@can('payment_settings_create')
<section id="paymentSettingsApp">
    <paymentsettings :data="{{ json_encode($data) }}"></paymentsettings>
</section>
@endcan
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <!-- <script src="{{ asset(mix('adminassets/js/collection/create.min.js')) }}"></script> -->
  <script src="{{ asset('js/admin/paymentsettings/app.js') }}"></script>
@endsection
