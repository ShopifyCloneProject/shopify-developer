@extends('layouts/contentLayoutMaster')
@section('title', 'Edit Collection')

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
  </style>
@endsection

@section('content')
<section id="addCollectionApp">
    <collection :list="{{ json_encode($list) }}" type="edit" :data="{{ json_encode($data) }}"></collection>
</section>
@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection

@section('page-script')
      <script type="text/javascript">
          let storeMediaUrl = '{{ route('admin.collections.storeMedia') }}';
      </script>
<!--  <script>
    Dropzone.options.srcDropzone = {
        url: '{{ route('admin.collections.storeMedia') }}',
        maxFilesize: 2, // MB
        acceptedFiles: '.jpeg,.jpg,.png,.gif',
        maxFiles: 1,
        addRemoveLinks: true,
        headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        maxfilesexceeded: function(file) {
            this.removeAllFiles();
            this.addFile(file);
        },
        success: function (file, response) {
          $('form').find('input[name="url"]').remove()
          $('form').append('<input type="hidden" name="url" value="' + response.name + '" class="collection-file">')
        },
        removedfile: function (file) {
          file.previewElement.remove()
          if (file.status !== 'error') {
            $('form').find('input[name="url"]').val('')
            this.options.maxFiles = this.options.maxFiles + 1
          }
        },
        init: function () {
            @if(isset($collection) && $collection->src)
                  var file = {!! json_encode($collection->src) !!}
                      this.options.addedfile.call(this, file)
                  this.options.thumbnail.call(this, file, file.preview)
                  file.previewElement.classList.add('dz-complete')
                  $('form').append('<input type="hidden" name="url" value="' + file.file_name + '">')
                  this.options.maxFiles = this.options.maxFiles - 1
            @endif
        },
        error: function (file, response) {
            if ($.type(response) === 'string') {
                var message = response //dropzone sends it's own error messages in string
            } else {
                var message = response.errors.file
            }
            file.previewElement.classList.add('dz-error')
            _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
            _results = []
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                node = _ref[_i]
                _results.push(node.textContent = message)
            }

            return _results
        }
    }
  </script> -->
  <script type="text/javascript">
      $(document).ready(function(){
        $('[data-toggle="popover"]').popover();

      })
  </script>
  <script src="{{ asset('js/admin/collection/app.js') }}"></script>
@endsection
