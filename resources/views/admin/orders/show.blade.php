@extends('layouts/contentLayoutMaster')
@section('title', 'View Order')

@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-ecommerce.css')) }}">
@endsection

@section('content')

<section id="orderProductApp" class="ecommerce-application">
    <ordersummary :list="{{ json_encode($list) }}" type="view" :data="{{ json_encode($data) }}" :globalsettings="{{ json_encode($globalSettings) }}"></ordersummary>
</section>
@endsection

@section('vendor-script')
@endsection

@section('page-script')
<script type="text/javascript">
  let getListingUrl = "{{ route('admin.shippingproducts.index') }}";
</script>
    <script src="{{ asset(mix('js/admin/order/app.js')) }}"></script>
@endsection
