@extends('layouts/contentLayoutMaster')
@section('title', 'Local Pickup')

@section('vendor-style')
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
 
@endsection

@section('content')
<section id="shippingSettingsApp">
    <localpickup :data="{{ json_encode($data) }}"></localpickup>
</section>
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <!-- <script src="{{ asset(mix('adminassets/js/collection/create.min.js')) }}"></script> -->
  <script src="{{ asset('js/admin/shippingsettings/app.js') }}"></script>
@endsection
