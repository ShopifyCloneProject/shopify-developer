
@extends('layouts/contentLayoutMaster')
@section('title', 'Dashboard Ecommerce')
@section('vendor-style')
  {{-- vendor css files --}}
@endsection
@section('page-style')
  {{-- Page css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/dashboard-ecommerce.css')) }}">
@endsection

@section('content')
<!-- Dashboard Ecommerce Starts -->
<section id="dashboard-ecommerce">
  <dashboard :data="{{ json_encode($data) }}" :globalsettings="{{ json_encode($globalSettings) }}"></dashboard>
  
</section>
<!-- Dashboard Ecommerce ends -->
@endsection

@section('vendor-script')
  {{-- vendor files --}}
@endsection
@section('page-script')
  {{-- Page js files --}}
  <script src="{{ asset('js/admin/dashboard/app.js') }}"></script>
@endsection
