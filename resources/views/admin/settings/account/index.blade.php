@extends('layouts/contentLayoutMaster')
@section('title', 'Account Settings')

@section('vendor-style')
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
 
@endsection

@section('content')
@can('account_create')
<section id="accountSettingsApp">
    <accountsettings :data="{{ json_encode($data) }}"></accountsettings>
</section>
@endcan
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <!-- <script src="{{ asset(mix('adminassets/js/collection/create.min.js')) }}"></script> -->
  <script src="{{ asset('js/admin/accountsettings/app.js') }}"></script>
@endsection
