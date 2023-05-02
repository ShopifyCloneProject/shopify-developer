@extends('layouts/contentLayoutMaster')
@section('title', 'Refund Order')

@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-ecommerce.css')) }}">
@endsection

@section('content')
<section id="orderProductApp" class="ecommerce-application">
    <refundparticularorder :data="{{ json_encode($data) }}"  :list="{{ json_encode($list) }}" :globalsettings="{{ json_encode($globalSettings) }}"></refundparticularorder>
</section>
@endsection

@section('vendor-script')

@endsection

@section('page-script')
    <script src="{{ asset(mix('js/admin/order/app.js')) }}"></script>
@endsection

