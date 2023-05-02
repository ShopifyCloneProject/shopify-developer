@extends('layouts/contentLayoutMaster')
@section('title', 'Locations')

@section('vendor-style')
@endsection
@section('page-style')
@endsection

@section('content')
@can('gift_card_create')
<section id="giftcardSettingsApp">
    <giftcardsettings :data="{{ json_encode($data) }}"></giftcardsettings>
</section>
@endcan
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <script src="{{ asset('js/admin/giftcardsettings/app.js') }}"></script>
@endsection
