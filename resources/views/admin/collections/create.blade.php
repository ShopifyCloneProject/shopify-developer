@extends('layouts/contentLayoutMaster')
@section('title', 'Add Collection')

@section('vendor-style')
  <!-- vendor css files -->
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
@section('page-style')
  <!-- Page css files -->
   <style type="text/css">
    .vue-dropzone:hover{
      background-color: #fff;
    }
    .dropzone .dz-preview:hover {
        z-index: 0;
        opacity: 0.3 !important;
    }
    .dropzone.dz-clickable *{
      cursor: pointer;
    }
    .dropzone .dz-preview:hover .dz-image img {
        transform: none !important;
        filter: none !important;
    }
   .dropzone .dz-preview:hover .dz-details{
        opacity: 0 !important;
    }
    div#dropzone {
        text-align: center;
    }
    .dz-error-message, .dz-success-mark, .dz-error-mark{
      display: none;
    }
  </style>
@endsection

@section('content')
<section id="addCollectionApp">
    <collection :list="{{ json_encode($list) }}" type="add" data=""></collection>
</section>
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
    <script type="text/javascript">
       let storeMediaUrl = '{{ route('admin.collections.storeMedia') }}';
    </script>
 
  <!-- <script src="{{ asset(mix('adminassets/js/collection/create.min.js')) }}"></script> -->
  <script src="{{ asset('js/admin/collection/app.js') }}"></script>
@endsection
