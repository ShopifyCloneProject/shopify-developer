@extends('email.template')
@section('email.main')
<style type="text/css" media="screen">
  p{
    text-align: left;
    padding-left: 50px;
  }
</style>

<div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2">

    <div style="border-bottom:1px solid #eee">
      <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">{{Config::get('app.name')}}</a>
    </div>
    <p style="font-size:1.1em">Hi,</p>
    <p>Thank you for choosing {{Config::get('app.name')}}. Use the following OTP to complete your Sign Up procedures. OTP is valid for 5 minutes</p>
    <h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">{{ $data['otp'] }}</h2>
    <p style="font-size:0.9em;">Regards,<br />{{Config::get('app.name')}}</p>
    <hr style="border:none;border-top:1px solid #eee" />
    
 
</div>
@endsection