@extends('layouts/contentLayoutMaster')
@section('title', 'Add Rates')

@section('vendor-style')
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
 
@endsection

@can('add_rates_access')
@section('content')
<section id="shippingSettingsApp">
    <addeditrates :data="{{ json_encode($data) }}" :globalsettings="{{ json_encode($globalSettings) }}"></addeditrates>
</section>
@endsection
@endcan

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <!-- <script src="{{ asset(mix('adminassets/js/collection/create.min.js')) }}"></script> -->
  <script src="{{ asset('js/admin/shippingsettings/app.js') }}"></script>
@endsection
