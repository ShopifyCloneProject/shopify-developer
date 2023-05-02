@extends('layouts/contentLayoutMaster')
@section('title', 'Edit Themes')

@section('vendor-style')
@endsection
@section('page-style')
@endsection

@section('content')
<section id="themeSelectionApp">
    <createtheme :list="{{ json_encode($list) }}" :data="{{ json_encode($themes) }}" type="edit"></createtheme>
</section>
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
  <!-- <script src="{{ asset(mix('adminassets/js/themes/create.min.js')) }}"></script> -->
  <script type="text/javascript">
  	let selectThemeUrl = "{{ url('admin/settings/selectedtheme') }}";
  </script>
  <script src="{{ asset('js/admin/themes/app.js') }}"></script>
@endsection
