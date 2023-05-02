@extends('layouts/contentLayoutMaster')
@section('title', 'Checkout Settings')

@section('vendor-style')
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
 
@endsection

@section('content')
@can('checkout_create')
<section id="checkoutSettingsApp">
    <checkoutsettings :data="{{ json_encode($data) }}"></checkoutsettings>
</section>
@endcan
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <!-- <script src="{{ asset(mix('adminassets/js/collection/create.min.js')) }}"></script> -->
  <script src="{{ asset('js/admin/checkoutsettings/app.js') }}"></script>
@endsection
