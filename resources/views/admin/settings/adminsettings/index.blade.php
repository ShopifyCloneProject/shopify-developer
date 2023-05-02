@extends('layouts/contentLayoutMaster')

@section('title', 'admin Settings')

@section('vendor-style')
  <!-- vendor css files -->
  <link rel='stylesheet' href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel='stylesheet' href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection
@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <style>
    .alert{
      padding: 20px !important;
    }
    
  </style>
@endsection
@section('content')
@can('admin_settings_create')

<section>
  <div class="row">
    <div class="col-md-9">
      @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
        </div>
      @endif
      @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
        </div>
      @endif
      <div class="card">
        <div class="card-body">
          <div class="tab-content">
           
            @can('account_settings_general')
            <div class="row">
              <form method="POST" action="{{ route('admin.settings.adminsettings.addEdit')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="resetIconImage" value="0" id="resetIconImage">
                <input type="hidden" name="resetLogoImage" value="0" id="resetLogoImage">
               
                <div class="hidden"></div>
                <div class="col-12 mb-2">
                  <label class="d-block mb-1" for="icon">{{ trans('cruds.adminsettings.fields.icon') }}</label>
                  <div class="media">
                  @if(!empty($objAdminSetting))
                    <a href="javascript:void(0);" class="mr-25">
                      <img
                      src="{{$objAdminSetting->icon}}"
                      id="icon-upload-img"
                      class="rounded mr-50"
                      alt="icon_image"
                      height="80"
                      width="80"
                      />
                    </a>
                    @else
                    <a href="javascript:void(0);" class="mr-25">
                      <img
                      src="{{asset('images/ico/favicon.ico')}}"
                      id="icon-upload-img"
                      class="rounded mr-50"
                      alt="icon_image"
                      height="80"
                      width="80"
                      />
                    </a>
                    @endif
                  <!-- upload and reset button -->
                  <div class="media-body mt-75 ml-1">
                    <label for="icon-upload" class="btn btn-sm btn-primary mb-75 mr-75" onclick="$('#resetIconImage').val(0)">Upload</label>
                    <input type="file" class="form-control" name="icon" id="icon-upload" hidden  />
                    <button type="reset" class="btn btn-sm btn-outline-secondary mb-75" id="rseet_icon">Reset</button>
                    <p>Allowed JPG, GIF or PNG. Max size of 800kB</p>
                  </div>

                </div>
                </div>
                <div class="col-12 mb-2">
                    <label class="d-block mb-1" for="logo">{{ trans('cruds.adminsettings.fields.logo') }}</label>
                   <div class="media">
                  @if(!empty($objAdminSetting))
                    <a href="javascript:void(0);" class="mr-25">
                      <img
                      src="{{$objAdminSetting->logo}}"
                      id="logo-upload-img"
                      class="rounded mr-50"
                      alt="logo_image"
                      height="80"
                      width="80"
                      />
                    </a>
                    @else
                    <a href="javascript:void(0);" class="mr-25">
                    <img
                    src="{{asset('images/logo/logo.png')}}"
                    id="logo-upload-img"
                    class="rounded mr-50"
                    alt="logo_image"
                    height="80"
                    width="80"
                    />
                  </a>
                  @endif
                     
                  <!-- upload and reset button -->
                  <div class="media-body mt-75 ml-1">
                    <label for="logo-upload" class="btn btn-sm btn-primary mb-75 mr-75" onclick="$('#resetLogoImage').val(0)">Upload</label>
                    <input type="file" class="form-control" name="logo" id="logo-upload" hidden  />
                    <button type="reset" class="btn btn-sm btn-outline-secondary mb-75" id="reset_logo">Reset</button>
                    <p>Allowed JPG, GIF or PNG. Max size of 800kB</p>
                  </div>
                  <!--/ upload and reset button -->

                </div>
                </div>
              
               
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="title">{{ trans('cruds.adminsettings.fields.title') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="title"
                        name="title"
                        placeholder="Enter title"
                        value="{{$objAdminSetting->title ?? ''}}"
                      />
                    </div>
                  </div>
                  <div class="col-12">
                    <button type="submit" class="btn btn-primary mr-1 mt-1">{{ trans('global.submit') }}</button>
                  <button type="reset" class="btn btn-outline-secondary mt-1">{{ trans('global.cancel') }}</button>
                  </div>
                </div>
              </form>
              <!--/ form -->
            </div>
            @endcan
            <!--/ general tab -->
          </div>
        </div>
      </div>
    </div> 
  
  </div>
</section>

@endcan
@endsection

@section('vendor-script')
  <!-- vendor files -->
  {{-- select2 min js --}}
  {{--  jQuery Validation JS --}}
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/dropzone.min.js')) }}"></script>
@endsection

@section('page-script')

 <script src="{{ asset(mix('adminassets/js/adminsettings/adminsettings.min.js')) }}"></script>

 <script type="text/javascript">
      $(document).ready(function () {
          $('#reset_icon').click(function(){
            $('#resetIconImage').val(1);
          $('#icon-upload-img').attr('src','{{asset('images/ico/favicon.ico')}}')
        })


          $('#reset_logo').click(function(){
            $('#resetLogoImage').val(1);
          $('#logo-upload-img').attr('src','{{asset('images/logo/logo.png')}}');
        })
      })
   </script>
   
@endsection