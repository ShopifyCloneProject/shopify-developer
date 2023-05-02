@extends('layouts/contentLayoutMaster')
@section('title',"$type Orders")

@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection
@section('page-style')
@endsection

@section('content')
<section id="orderProductApp">
    <createorder :list="{{ json_encode($list) }}" :data="{{ json_encode($data) }}"  type="{{$type}}"></createorder>
</section>
@endsection

@section('vendor-script')
  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

@section('page-script')
  <script>

    let getListingUrl = "{{ route('admin.orders.index') }}";
    </script> 
  <script src="{{ asset('js/admin/order/app.js') }}"></script>
@endsection
