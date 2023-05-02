@extends('layouts/contentLayoutMaster')
@section('title', 'Edit Customer')

@section('vendor-style')
  <!-- vendor css files -->
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
 
@endsection

@section('content')
<section id="customerApp">
    <editcustomer :list="{{ json_encode($list) }}" type="edit" :data="{{ json_encode($data) }}"></editcustomer>
</section>
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <script src="{{ asset(mix('js/admin/customer/app.js')) }}"></script>
@endsection
