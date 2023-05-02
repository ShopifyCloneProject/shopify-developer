@extends('layouts/contentLayoutMaster')
@section('title',"Cart Product")

@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection
@section('page-style')
@endsection

@section('content')
<section id="cartProductApp">
    <createcartproduct :list="{{ json_encode($list) }}" :data="{{ json_encode($data) }}"  type="{{$type}}"></createcartproduct>
</section>
@endsection

@section('vendor-script')
  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

@section('page-script')
  <!-- <script src="{{ asset(mix('adminassets/js/themes/create.min.js')) }}"></script> -->

  <script src="{{ asset('js/admin/cart/app.js') }}"></script>
@endsection
