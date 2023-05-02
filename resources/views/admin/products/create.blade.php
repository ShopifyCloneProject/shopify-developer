@extends('layouts/contentLayoutMaster')
@section('title')
  {{ trans('global.add') }} {{ trans('cruds.product.title_singular') }}
@endsection

@section('content')

@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

<section id="variantsSection">
   <product :list="{{ json_encode($list) }}"></product>
</section>
<!-- Input Sizing end -->
@endsection


@section('vendor-script')
  <!-- vendor files -->
   <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
   <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection
@section('page-script')
  <!-- Page js files -->
  <script src="{{ asset('js/admin/product/app.js') }}"></script>
@endsection
