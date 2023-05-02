@extends('client.client')
@section('content')
  <div id="home">
    <maindata :data="{{ json_encode($data) }}"  ></maindata>
  </div>  
@stop
@section('page-script')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@stop