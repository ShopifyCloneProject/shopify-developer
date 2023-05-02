@extends('layouts/contentLayoutMaster')
@section('title','Discounts')
{{-- @section('title', "$type Discounts") --}}

@section('vendor-style')
  <!-- vendor css files -->
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection
@section('page-style')
 
@endsection

@section('content')
<section id="discountsApp">
     {{-- <createupdatediscounts :list="{{ json_encode($list) }}"></createupdatediscounts> --}}
     <createupdatediscounts :list="{{ json_encode($list) }}" :data="{{ json_encode($data) }}" type="{{$type}}"></createupdatediscounts>
</section>
@endsection

@section('vendor-script')
  <!-- vendor files -->
   <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

@section('page-script')
  <script type="text/javascript">
     let getListingUrl = "{{ route('admin.discounts.index') }}"
  </script>
  <script src="{{ asset(mix('js/admin/discounts/app.js')) }}"></script>
@endsection


