@extends('layouts/contentLayoutMaster')
@section('title', 'Taxes Settings')

@section('vendor-style')
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
 
@endsection

@section('content')
@can('tax_access_create')
<section id="taxesSettingsApp">
    <taxessettings :data="{{ json_encode($data) }}" :list="{{ json_encode($list) }}"></taxessettings>
</section>
@endcan
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <!-- <script src="{{ asset(mix('adminassets/js/collection/create.min.js')) }}"></script> -->
  <script src="{{ asset('js/admin/taxessettings/app.js') }}"></script>
@endsection
