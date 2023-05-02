@extends('layouts/contentLayoutMaster')
@section('title', "$type Customer")

@section('vendor-style')
  <!-- vendor css files -->
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
 
@endsection

@section('content')
<section id="customerApp">
     <addeditcustomer :list="{{ json_encode($list) }}" :data="{{ json_encode($data) }}" type="{{$type}}"></addeditcustomer>
</section>
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <script type="text/javascript">
     let getListingUrl = "{{ route('admin.customers.index') }}"
  </script>
  <script src="{{ asset(mix('js/admin/customer/app.js')) }}"></script>
@endsection


