@extends('layouts/contentLayoutMaster')
@section('title', 'View Order')

@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-ecommerce.css')) }}">
@endsection

@section('content')
<section id="customerApp" class="ecommerce-application">
    <showcustomer :list="{{ json_encode($list) }}" :data="{{ json_encode($data) }}" :globalsettings="{{ json_encode($globalSettings) }}"></showcustomer>
  
</section>
@endsection

@section('vendor-script')

@endsection

@section('page-script')
    <script src="{{ asset(mix('js/admin/customer/app.js')) }}"></script>
@endsection
