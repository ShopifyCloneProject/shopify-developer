@extends('layouts/contentLayoutMaster')
@section('title', 'Payment Methods')
@section('vendor-style')
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
 
@endsection

@section('content')
@can('payment_details_access')
<section id="paymentSettingsApp">
    <paymentdetails :data="{{ json_encode($data) }}"></paymentdetails>
</section>
@endcan
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <script src="{{ asset('js/admin/paymentsettings/app.js') }}"></script>
@endsection
