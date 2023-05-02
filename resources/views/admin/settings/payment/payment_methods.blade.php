@extends('layouts/contentLayoutMaster')
@section('title', 'Payment Methods')

@section('vendor-style')
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
 
@endsection

@can('payment_meethods_create')
    <input type="hidden" id="payment_meethods_create" value="1">
@endcan
@section('content')
@can('payment_meethods_access')
<section id="paymentSettingsApp">
    <paymentmethods :data="{{ json_encode($data) }}"></paymentmethods>
</section>
@endcan
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <script src="{{ asset('js/admin/paymentsettings/app.js') }}"></script>
@endsection
