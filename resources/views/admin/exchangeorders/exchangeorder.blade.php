@extends('layouts/contentLayoutMaster')
@section('title',"Exchange Orders")

@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection
@section('page-style')
@endsection

@section('content')
<section id="exchangeordersApp">
    <exchangeorders :list="{{ json_encode($list) }}" :data="{{ json_encode($data) }}" :globalsettings="{{ json_encode($globalSettings) }}"></exchangeorders>
</section>
@endsection

@section('vendor-script')
  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

@section('page-script')
<script type="text/javascript">
</script>
  <script src="{{ asset('js/admin/exchangeorders/app.js') }}"></script>
@endsection
