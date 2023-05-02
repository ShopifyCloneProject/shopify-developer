@extends('layouts/contentLayoutMaster')
@section('title', 'Plan')

@section('vendor-style')
@endsection
@section('page-style')
@endsection

@section('content')
@can('plan_create')
<section id="planSettingsApp">
    <plansettings :data="{{ json_encode($data) }}"></plansettings>
</section>
@endcan
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <script src="{{ asset('js/admin/plansettings/app.js') }}"></script>
@endsection
