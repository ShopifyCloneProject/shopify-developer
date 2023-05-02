@extends('layouts/contentLayoutMaster')

@section('title', 'Account Settings')
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
@can('manage_account_acccess')
<!-- account setting page -->
<section id="page-account-settings">
  <div class="row">
    <!-- left menu section -->
    <div class="col-md-3 mb-2 mb-md-0">
      <ul class="nav nav-pills flex-column nav-left">
        <!-- general -->
        @can('account_settings_general')
        <li class="nav-item">
          <a
            class="nav-link active"
            id="account-pill-general"
            data-toggle="pill"
            href="#account-vertical-general"
            aria-expanded="true"
          >
            <i data-feather="user" class="font-medium-3 mr-1"></i>
            <span class="font-weight-bold">{{ trans('global.general') }}</span>
          </a>
        </li>
        @endcan
        <!-- change password -->

        @can('account_change_password')
        <li class="nav-item">
          <a
            class="nav-link"
            id="account-pill-password"
            data-toggle="pill"
            href="#account-vertical-password"
            aria-expanded="false"
          >
            <i data-feather="lock" class="font-medium-3 mr-1"></i>
            <span class="font-weight-bold">{{ trans('global.change_password') }}</span>
          </a>
        </li>
        @endcan
        <!-- information -->

        @can('account_information')
        <li class="nav-item">
          <a
            class="nav-link"
            id="account-pill-info"
            data-toggle="pill"
            href="#account-vertical-info"
            aria-expanded="false"
          >
            <i data-feather="info" class="font-medium-3 mr-1"></i>
            <span class="font-weight-bold">Information</span>
          </a>
        </li>
        @endcan
        <!-- social -->

        @can('account_social_link')
        <li class="nav-item">
          <a
            class="nav-link"
            id="account-pill-social"
            data-toggle="pill"
            href="#account-vertical-social"
            aria-expanded="false"
          >
            <i data-feather="link" class="font-medium-3 mr-1"></i>
            <span class="font-weight-bold">Social</span>
          </a>
        </li>
        @endcan
        <!-- notification -->

       {{--  @can('account_settings_notification')
        <li class="nav-item">
          <a
            class="nav-link"
            id="account-pill-notifications"
            data-toggle="pill"
            href="#account-vertical-notifications"
            aria-expanded="false"
          >
            <i data-feather="bell" class="font-medium-3 mr-1"></i>
            <span class="font-weight-bold">Notifications</span>
          </a>
        </li>
        @endcan --}}
      </ul>
    </div>
    <!--/ left menu section -->

    <!-- right content section -->
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
            <!-- general tab -->
            @can('account_settings_general')
            <div
              role="tabpanel"
              class="tab-pane active"
              id="account-vertical-general"
              aria-labelledby="account-pill-general"
              aria-expanded="true"
            >
              <!-- header media -->

              <form method="POST" action="{{ url('admin/manage-account/update-profile')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="resetProfile" value="0" id="resetProfile">
                <div class="media">
                  @if(file_exists(public_path($userData->image)))
                  <a href="javascript:void(0);" class="mr-25">
                    <img
                    src="{{$userData->image}}"
                    id="account-upload-img"
                    class="rounded mr-50"
                    alt="profile image"
                    height="80"
                    width="80"
                    />
                  </a>
                  @else
                  <a href="javascript:void(0);" class="mr-25">
                    <img
                    src="{{asset('/images/avatars/11-small.png')}}"
                    id="account-upload-img"
                    class="rounded mr-50"
                    alt="profile image"
                    height="80"
                    width="80"
                    />
                  </a>
                  @endif
                    
                  <!-- upload and reset button -->
                  <div class="media-body mt-75 ml-1">
                    <label for="account-upload" class="btn btn-sm btn-primary mb-75 mr-75" onclick="$('#resetProfile').val(0)">Upload</label>
                    <input type="file" class="form-control" name="image" id="account-upload" hidden value="" />
                    <button type="reset" class="btn btn-sm btn-outline-secondary mb-75" id="reset_img">Reset</button>
                    <p>Allowed JPG, GIF or PNG. Max size of 800kB</p>
                  </div>
                  <!--/ upload and reset button -->

                </div>
              
              <!--/ header media -->

              <!-- form -->
              
                <div class="row">
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="account-username">Username</label>
                      <input
                        type="text"
                        class="form-control"
                        id="account-username"
                        name="username"
                        placeholder="Username"
                        value="{{$userData->username}}"
                      />
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="account-name">Name</label>
                      <input
                        type="text"
                        class="form-control"
                        id="account-name"
                        name="name"
                        placeholder="Name"
                        value="{{$userData->name}}"
                      />
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="account-e-mail">E-mail</label>
                      <input
                        type="email"
                        class="form-control"
                        id="account-e-mail"
                        name="email"
                        placeholder="Email"
                        value="{{$userData->email}}"
                        disabled
                      />
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="account-company">Company</label>
                      <input
                        type="text"
                        class="form-control"
                        id="account-company"
                        name="company"
                        placeholder="Company name"
                        value="{{$userData->company}}"
                      />
                    </div>
                  </div>
                
                  @if($userData->email_verified_at == null)
                  <div class="col-12 col-sm-6 verify_email">
                    <div class="form-group">
                      <label for="account-name">Enter Your OTP</label>
                      <input
                        type="number"
                        class="form-control"
                        id="verifyotp"
                        name="verifyotp"
                        placeholder="OTP"
                        value=""
                      />
                    </div>
                  </div>
                  <div class="col-12 col-sm-6 verify_email">
                    <div class="form-group">
                      <button type="button" class="btn btn-primary mt-2 mr-1" id="btnVerifyOtp">Verify OTP</button>
                    </div>
                  </div>
                  <div class="col-12 verify_email">
                    <a href="#" class="link-primary" id="resendOTP">Resent OTP</a>
                  </div>
                  @endif
                  <div class="col-12">
                    <button type="submit" class="btn btn-primary mt-2 mr-1" onclick="{{ route('admin.update-profile') }}">Save changes</button>
                    <button type="reset" class="btn btn-outline-secondary mt-2">Cancel</button>
                  </div>
                </div>
              </form>
              <!--/ form -->
            </div>
            @endcan
            <!--/ general tab -->

            <!-- change password -->
            @can('account_change_password')
            <div
              class="tab-pane fade"
              id="account-vertical-password"
              role="tabpanel"
              aria-labelledby="account-pill-password"
              aria-expanded="false"
            >
              <!-- form -->
              <form id="change-password"  action="{{ route("profile.password.updateProfile") }}">
                <div class="row">
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="account-old-password">{{ trans('global.current_password') }}</label>
                      <div class="input-group form-password-toggle input-group-merge">
                        <input
                          type="password"
                          class="form-control"
                          id="current_password"
                          name="current_password"
                          placeholder="{{ trans('global.current_password') }}"
                        />
                        <div class="input-group-append">
                          <div class="input-group-text cursor-pointer">
                            <i data-feather="eye"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="account-new-password">{{ trans('global.new_password') }}</label>
                      <div class="input-group form-password-toggle input-group-merge">
                        <input
                          type="password"
                          id="new_password"
                          name="new_password"
                          class="form-control"
                          placeholder="{{ trans('global.new_password') }}"
                        />
                        <div class="input-group-append">
                          <div class="input-group-text cursor-pointer">
                            <i data-feather="eye"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="account-retype-new-password">{{ trans('global.confirm_password') }}</label>
                      <div class="input-group form-password-toggle input-group-merge">
                        <input
                          type="password"
                          class="form-control"
                          id="confirm-new-password"
                          name="confirm-new-password"
                          placeholder="{{ trans('global.confirm_password') }}"
                        />
                        <div class="input-group-append">
                          <div class="input-group-text cursor-pointer"><i data-feather="eye"></i></div>
                        </div>
                      </div>
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
            <!--/ change password -->
            @endcan
            <!-- information -->

            @can('account_information')
            <div
              class="tab-pane fade"
              id="account-vertical-info"
              role="tabpanel"
              aria-labelledby="account-pill-info"
              aria-expanded="false"
            >
              <!-- form -->
              <form class="validate-form" id="user-information"  action="{{ url('admin/manage-account/account-information')}}" method="POST">
                @csrf
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="accountTextarea">Bio</label>
                      <textarea
                        class="form-control"
                        id="accountTextarea"
                        rows="4"
                        placeholder="Your Bio data here..."
                        name="bio"
                      >{{ $userDetail->bio ?? ''}}</textarea>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="account-birth-date">Birth date</label>
                      <input
                        type="text"
                        class="form-control flatpickr"
                        placeholder="Birth date"
                        id="account-birth-date"
                        name="birth_date"
                        value="{{$userDetail->birth_date ?? ''}}"
                      />
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="accountSelect">Country</label>

                      <select class="form-control" id="accountSelect" name="country_id">
                        @foreach($countries as $country)
                          <option value="{{ $country->id }}" {{ (isset($userDetail->country_id)? ($userDetail->country_id == $country->id)? 'selected': '' : '' ) }}>{{ $country->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="account-website">Website</label>
                      <input
                        type="text"
                        class="form-control"
                        name="website"
                        id="account-website"
                        placeholder="Website address"
                        value="{{$userDetail->website ?? ''}}"
                      />
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="account-phone">Phone</label>
                      <input
                        type="text"
                        class="form-control"
                        id="account-phone"
                        placeholder="Phone number"
                        name="phone"
                        value="{{$userDetail->phone ?? ''}}"
                      />
                    </div>
                  </div>
                  <div class="col-12">
                    <button type="submit" class="btn btn-primary mt-1 mr-1">Save changes</button>
                    <button type="reset" class="btn btn-outline-secondary mt-1">Cancel</button>
                  </div>
                </div>
              </form>
              <!--/ form -->
            </div>
            @endcan
            <!--/ information -->

            <!-- social -->
            @can('account_social_link')
            <div
              class="tab-pane fade"
              id="account-vertical-social"
              role="tabpanel"
              aria-labelledby="account-pill-social"
              aria-expanded="false"
            >
              <!-- form -->
              <form class="validate-form" id="social-links"  action="{{ url("admin/manage-account/social-links") }}" method="POST">
                @csrf
                <div class="row">
                  <!-- social header -->
                  <div class="col-12">
                    <div class="d-flex align-items-center mb-2">
                      <i data-feather="link" class="font-medium-3"></i>
                      <h4 class="mb-0 ml-75">Social Links</h4>
                    </div>
                  </div>
                  <!-- twitter link input -->
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="account-twitter">Twitter</label>
                      <input
                        type="text"
                        id="account-twitter"
                        name="twitter",
                        class="form-control"
                        placeholder="Add link"
                        value="{{ $userDetail->twitter ?? ''}}"
                      />
                    </div>
                  </div>
                  <!-- facebook link input -->
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="account-facebook">Facebook</label>
                      <input type="text" name="facebook" id="account-facebook" class="form-control" placeholder="Add link" value="{{ $userDetail->facebook ?? ''}}" />
                    </div>
                  </div>
                  <!-- google plus input -->
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="account-google">Google+</label>
                      <input type="text" name="google" id="account-google" class="form-control" placeholder="Add link" value="{{ $userDetail->google ?? '' }}" />
                    </div>
                  </div>
                  <!-- linkedin link input -->
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="account-linkedin">LinkedIn</label>
                      <input
                        type="text"
                        name="linkedin"
                        id="account-linkedin"
                        class="form-control"
                        placeholder="Add link"
                        value="{{ $userDetail->linkedin ?? ''}}"
                      />
                    </div>
                  </div>
                  <!-- instagram link input -->
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="account-instagram">Instagram</label>
                      <input type="text" name="instagram" id="account-instagram" class="form-control" placeholder="Add link" value="{{ $userDetail->instagram ?? '' }}" />
                    </div>
                  </div>
                  <!-- Quora link input -->
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="account-quora">Quora</label>
                      <input type="text" name="quora" id="account-quora" class="form-control" placeholder="Add link" value="{{ $userDetail->quora ?? ''}}" />
                    </div>
                  </div>

                  <!-- divider -->
                 {{--  <div class="col-12">
                    <hr class="my-2" />
                  </div>

                  <div class="col-12 mt-1"> --}}
                    <!-- profile connection header -->
                    {{-- <div class="d-flex align-items-center mb-3">
                      <i data-feather="user" class="font-medium-3"></i>
                      <h4 class="mb-0 ml-75">Profile Connections</h4>
                    </div>

                    <div class="row"> --}}
                      <!-- twitter user -->
                      {{-- <div class="col-6 col-md-3 text-center mb-1">
                        <p class="font-weight-bold">Your Twitter</p>
                        <div class="avatar mb-1">
                          <span class="avatar-content">
                            <img
                              src="{{asset('images/avatars/11-small.png')}}"
                              alt="avatar img"
                              width="40"
                              height="40"
                            />
                          </span>
                        </div>
                        <p class="mb-0">@johndoe</p>
                        <a href="javascript:void(0)">Disconnect</a>
                      </div> --}}
                      <!-- facebook button -->
                     {{--  <div class="col-6 col-md-3 text-center mb-1">
                        <p class="font-weight-bold mb-2">Your Facebook</p>
                        <button class="btn btn-outline-primary">Connect</button>
                      </div> --}}
                      <!-- google user -->
                      {{-- <div class="col-6 col-md-3 text-center mb-1">
                        <p class="font-weight-bold">Your Google</p>
                        <div class="avatar mb-1">
                          <span class="avatar-content">
                            <img
                              src="{{asset('images/avatars/3-small.png')}}"
                              alt="avatar img"
                              width="40"
                              height="40"
                            />
                          </span>
                        </div>
                        <p class="mb-0">@luraweber</p>
                        <a href="javascript:void(0)">Disconnect</a>
                      </div> --}}
                      <!-- github button -->
                     {{--  <div class="col-6 col-md-3 text-center mb-2">
                        <p class="font-weight-bold mb-1">Your GitHub</p>
                        <button class="btn btn-outline-primary">Connect</button>
                      </div>
                    </div>
                  </div> --}}
                  <div class="col-12">
                    <!-- submit and cancel button -->
                    <button type="submit" class="btn btn-primary mr-1 mt-1">Save changes</button>
                    <button type="reset" class="btn btn-outline-secondary mt-1">Cancel</button>
                  </div>
                </div>
              </form>
              <!--/ form -->
            </div>
            @endcan
            <!--/ social -->

            <!-- notifications -->
            @can('account_settings_notification')
            <div
              class="tab-pane fade"
              id="account-vertical-notifications"
              role="tabpanel"
              aria-labelledby="account-pill-notifications"
              aria-expanded="false"
            >
              <div class="row">
                <h6 class="section-label mx-1 mb-2">Activity</h6>
                <div class="col-12 mb-2">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" checked id="accountSwitch1" />
                    <label class="custom-control-label" for="accountSwitch1">
                      Email me when someone comments on my article
                    </label>
                  </div>
                </div>
                <div class="col-12 mb-2">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" checked id="accountSwitch2" />
                    <label class="custom-control-label" for="accountSwitch2">
                      Email me when someone answers on my form
                    </label>
                  </div>
                </div>
                <div class="col-12 mb-2">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="accountSwitch3" />
                    <label class="custom-control-label" for="accountSwitch3">Email me when someone follows me</label>
                  </div>
                </div>
                <h6 class="section-label mx-1 mt-2">Application</h6>
                <div class="col-12 mt-1 mb-2">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" checked id="accountSwitch4" />
                    <label class="custom-control-label" for="accountSwitch4">News and announcements</label>
                  </div>
                </div>
                <div class="col-12 mb-2">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" checked id="accountSwitch6" />
                    <label class="custom-control-label" for="accountSwitch6">Weekly product updates</label>
                  </div>
                </div>
                <div class="col-12 mb-75">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="accountSwitch5" />
                    <label class="custom-control-label" for="accountSwitch5">Weekly blog digest</label>
                  </div>
                </div>
                <div class="col-12">
                  <button type="submit" class="btn btn-primary mt-2 mr-1">Save changes</button>
                  <button type="reset" class="btn btn-outline-secondary mt-2">Cancel</button>
                </div>
              </div>
            </div>
            @endcan
            <!--/ notifications -->
          </div>
        </div>
      </div>
    </div>
    <!--/ right content section -->
  </div>
</section>
<!-- / account setting page -->
@endcan
@endsection

@section('vendor-script')
  <!-- vendor files -->
  {{-- select2 min js --}}
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  {{--  jQuery Validation JS --}}
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/dropzone.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection
@section('page-script')
  <!-- Page js files -->
   <script type="text/javascript">
       let getListingUrl = "{{ route('admin.resend-otp') }}"
       let verifyUrl = "{{ route('admin.verify-otp') }}" 
    </script>
   <script src="{{ asset(mix('adminassets/js/user/user.min.js')) }}"></script>

   <script type="text/javascript">
   
       $(document).ready(function () {
          $('#reset_img').click(function(){
            $('#resetProfile').val(1);
            $('#account-upload-img').attr('src','{{asset('/images/avatars/11-small.png')}}');
         
        })
      })

   </script>
@endsection

