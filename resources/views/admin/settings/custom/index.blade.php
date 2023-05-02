@extends('layouts/contentLayoutMaster')
@section('title', 'Legal Settings')

@section('content')
<section id="customSettingsApp">
@can('custom_settings_create')
    <customsettings :data="{{ json_encode($data) }}"></customsettings>
@endcan
</section>
@endsection

@section('page-script')
  <script src="{{ asset(mix('js/admin/customsettings/app.js')) }}"></script>
@endsection
