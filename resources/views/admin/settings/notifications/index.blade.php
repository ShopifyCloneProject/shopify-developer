@extends('layouts/contentLayoutMaster')
@section('title', 'Notification Settings')

@section('vendor-style')
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
 
@endsection

@section('content')
@can('notification_settings_create')
<section id="notificationSettingsApp">
    <notifications :list="{{ json_encode($list) }}" :data="{{ json_encode($data) }}"></notifications>
</section>
@endcan
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <!-- <script src="{{ asset(mix('adminassets/js/collection/create.min.js')) }}"></script> -->
  <script src="{{ asset('js/admin/notificationsettings/app.js') }}"></script>
@endsection
