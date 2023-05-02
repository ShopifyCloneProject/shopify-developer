@extends('layouts/contentLayoutMaster')
@section('title',"Abandone Checkouts")

@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection
@section('page-style')
@endsection

@section('content')
<section id="abandoneCheckoutsApp">
    <abandonecheckouts :data="{{ json_encode($data) }}"></abandonecheckouts>
</section>
@endsection

@section('vendor-script')
  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

@section('page-script')
<script type="text/javascript">
  
  let getListingUrl = "{{ route('admin.abandonecheckouts.index') }}";
</script>
  <script src="{{ asset('js/admin/abandonecheckout/app.js') }}"></script>
@endsection
