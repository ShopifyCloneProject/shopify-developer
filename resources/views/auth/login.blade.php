@if(Auth::check())
  <script type="text/javascript">
   window.location = "{{ url('/dashboard') }}";
   </script>
@endif

@php
$configData = Helper::applClasses();
$configUserAdminData = Helper::getAdminIconLogoTitle();
@endphp

@extends('layouts/fullLayoutMaster')

@section('title', 'Login Page')

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/page-auth.css')) }}">

@endsection

@section('content')
<div class="auth-wrapper auth-v2">
  <div class="auth-inner row m-0">
      <!-- Brand logo-->
      
      <!-- /Brand logo-->
      <!-- Left Text-->
      <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
        <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
          @if($configData['theme'] === 'dark')
          <img class="img-fluid" src="{{asset('images/pages/login-v2-dark.svg')}}" alt="Login V2" />
          @else
          <img class="img-fluid" src="{{asset('images/pages/login-v2.svg')}}" alt="Login V2" />
          @endif
        </div>
      </div>
      <!-- /Left Text-->
      <!-- Login-->
      <div class=" col-lg-4 align-items-center auth-bg px-2 p-lg-5">
        <div class="row">
          <div class="col-12">
            <a class="brand-logo" href="javascript:void(0);">
            <img src="{{$configUserAdminData['admin_logo']}}" width="100">
          </a>
          </div>
        </div>
        <div class="row">
           <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">

          @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
            </div>
          @endif
          @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block py-1 px-1">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
            </div>
          @endif
          
          <h2 class="card-title font-weight-bold mb-1">Welcome to Admin! &#x1F44B;</h2>
          <p class="card-text mb-2">Please sign-in to your account and start the adventure</p>
          <form class="auth-login-form mt-2" method="POST" action="/admin/login">
            @csrf
            <div class="form-group">
              <label class="form-label" for="login-email">Email</label>
              <input class="form-control" id="login-email" type="text"  placeholder="Enter your email" name="email" aria-describedby="login-email" autofocus="" tabindex="1" value="{{ old('email') }}" />
            </div>
            <div class="form-group">
              <div class="d-flex justify-content-between">
                <label for="login-password">Password</label>
                <a href="{{url('admin/password/request')}}">
                  <small>Forgot Password?</small>
                </a>
              </div>
              <div class="input-group input-group-merge form-password-toggle">
                <input class="form-control form-control-merge" id="login-password" type="password" name="password" placeholder="············" aria-describedby="login-password" tabindex="2" />
                <div class="input-group-append">
                  <span class="input-group-text cursor-pointer">
                    <i data-feather="eye"></i>
                  </span>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div div class="custom-control custom-checkbox">
                <input class="custom-control-input" id="remember-me" type="checkbox" tabindex="3" {{ old('remember-me') ? 'checked' : '' }} />
                <label class="custom-control-label" for="remember-me">Remember Me</label>
              </div>
            </div>
            <button class="btn btn-primary btn-block" tabindex="4">Sign in</button>
          </form>
          <p class="text-center mt-2">
            {{-- <span>New on our platform?</span>
            <a href="{{url('auth/register-v2')}}"><span>&nbsp;Create an account</span></a> --}}
          </p>
          <div class="divider my-2">
            <div class="divider-text">or</div>
          </div>
          <div class="auth-footer-btn d-flex justify-content-center">
            <a class="btn btn-facebook" href="javascript:void(0)">
              <i data-feather="facebook"></i>
            </a>
            <a class="btn btn-twitter white" href="javascript:void(0)">
              <i data-feather="twitter"></i>
            </a>
            <a class="btn btn-google" href="javascript:void(0)">
              <i data-feather="mail"></i>
            </a>
            <a class="btn btn-github" href="javascript:void(0)">
              <i data-feather="github"></i>
            </a>
          </div>
      </div>
        </div>
       
    </div>
    <!-- /Login-->
  </div>
</div>
@endsection

@section('vendor-script')
<script src="{{asset(mix('vendors/js/forms/validation/jquery.validate.min.js'))}}"></script>
@endsection

@section('page-script')
<script src="{{asset(mix('js/scripts/pages/page-auth-login.js'))}}"></script>
@endsection
