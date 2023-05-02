@extends('layouts/contentLayoutMaster')
@section('title', 'Create Rates')

@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
 
@endsection

@section('content')
<section id="shippingSettingsApp">
    <createrates :data="{{ json_encode($data) }}"></createrates>
</section>
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <script src="{{ asset('js/admin/shippingsettings/app.js') }}"></script>
@endsection
