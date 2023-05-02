@extends('layouts/contentLayoutMaster')
@section('title', 'View Order')

@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
@endsection

@section('content')
<section id="returnshippingproductsApp" class="ecommerce-application">
    <returnshippingproducts :list="{{ json_encode($list) }}" :data="{{ json_encode($data) }}"></returnshippingproducts>
</section>
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
<script type="text/javascript">
  let getListingUrl = "{{ route('admin.returnshippingproducts.index') }}";
</script>
  <script src="{{ asset('js/admin/returnshippingproducts/app.js') }}"></script>
@endsection
