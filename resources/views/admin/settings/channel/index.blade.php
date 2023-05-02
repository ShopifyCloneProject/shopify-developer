@extends('layouts/contentLayoutMaster')
@section('title', 'Channel Settings')

@section('vendor-style')
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
 
@endsection

@section('content')
@can('sales_channel_access_create')
<section id="channelSettingsApp">
    <channelsettings :data="{{ json_encode($data) }}"></channelsettings>
</section>
@endcan
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <!-- <script src="{{ asset(mix('adminassets/js/collection/create.min.js')) }}"></script> -->
  <script src="{{ asset('js/admin/channelsettings/app.js') }}"></script>
@endsection
