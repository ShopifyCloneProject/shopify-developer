@extends('layouts/contentLayoutMaster')
@section('title',"$type Notification")

@section('vendor-style')
@endsection
@section('page-style')
@endsection

@section('content')
<section id="notificationSettingsApp">
    <notificationsettings :list="{{ json_encode($list) }}" :data="{{ json_encode($data) }}"  type="{{$type}}"></notificationsettings>
</section>
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
 
  <script src="{{ asset('js/admin/notificationsettings/app.js') }}"></script>
@endsection


