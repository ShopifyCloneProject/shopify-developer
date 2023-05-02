@extends('layouts/contentLayoutMaster')
@section('title', 'Shipping Details')

@section('vendor-style')
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
 
@endsection
@section('content')
@can('shipping_details_create')
<section id="shippingDetailsApp">
    <shippingdetails :data="{{ json_encode($data) }}" ></shippingdetails>
</section>
@endcan
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <!-- <script src="{{ asset(mix('adminassets/js/collection/create.min.js')) }}"></script> -->
  <script src="{{ asset('js/admin/shippingdetails/app.js') }}"></script>
@endsection
