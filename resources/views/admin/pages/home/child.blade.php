@extends('layouts/contentLayoutMaster')

@section('vendor-style')
   @include('admin/partials/datatableCss')
@endsection
@section('page-style')

@endsection

@section('title')
  {{ trans('cruds.pages.page_settings') }}
@endsection

@section('content')
<section id="pagesettings">
  <childpage :list="{{ json_encode($list) }}"></childpage>
</section>
@endsection

@section('vendor-script')
 <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('js/admin/pagessettings/app.js') }}"></script>
@endsection