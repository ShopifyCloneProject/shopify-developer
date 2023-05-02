@extends('layouts/contentLayoutMaster')
@section('title', 'View Order')

@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
@endsection

@section('content')
<section id="shippingProductApp" class="ecommerce-application">
    <shippingproducts :list="{{ json_encode($list) }}" :data="{{ json_encode($data) }}"></shippingproducts>
</section>
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
<script type="text/javascript">
  let getListingUrl = "{{ route('admin.shippingproducts.index') }}";
</script>
  <script src="{{ asset('js/admin/shippingproducts/app.js') }}"></script>
@endsection
