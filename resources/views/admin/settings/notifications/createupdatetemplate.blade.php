@extends('layouts/contentLayoutMaster')
@section('title', 'Notification Template')

@section('vendor-style')
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
 
@endsection

@section('content')
@can('notification_template_create')
<section id="notificationSettingsApp">
    <createupdatetemplate :data="{{ json_encode($data) }}"></createupdatetemplate>
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
