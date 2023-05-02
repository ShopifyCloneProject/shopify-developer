@extends('layouts/contentLayoutMaster')
@section('title', 'Shipping Settings')

@section('vendor-style')
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
 
@endsection

@can('shipping_access_create')
@section('content')
<section id="shippingSettingsApp">
    <shippingsettings :data="{{ json_encode($data) }}"></shippingsettings>
</section>
@endsection
@endcan

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <!-- <script src="{{ asset(mix('adminassets/js/collection/create.min.js')) }}"></script> -->
  <script src="{{ asset('js/admin/shippingsettings/app.js') }}"></script>
@endsection
