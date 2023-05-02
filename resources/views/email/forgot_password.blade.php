@extends('email.template')
@section('email.main')
<!-- big image section -->
<tr>
    <td align="center" class="sm-px-24" style="font-family: 'Montserrat',Arial,sans-serif;">
        <table style="font-family: 'Montserrat',Arial,sans-serif; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td align="left">
                    <table border="0" width="590" align="center" cellpadding="0" cellspacing="0" class="containerlifelancer">
                        <tr>
                            <td class="sm-px-24" style="--bg-opacity: 1; background-color: #ffffff; background-color: rgba(255, 255, 255, var(--bg-opacity)); border-radius: 4px; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; font-size: 14px; line-height: 24px; padding: 48px; text-align: left; --text-opacity: 1; color: #626262; color: rgba(98, 98, 98, var(--text-opacity));" bgcolor="rgba(255, 255, 255, var(--bg-opacity))" align="left">
                                <!-- section text ======-->
                                <div style="line-height: 35px;text-align:center;">
                                        Forgot your <span style="color: #000;"> password?</span>
                                </div>

                                <p style="line-height: 24px; margin-bottom:15px;">Hello!
                                </p>
                                    <p style="line-height: 24px;margin-bottom:15px;">
                                     You recently requested for a password reset for your {{ Config::get('app.name') }} account, click the button below to reset your password. 
                                    </p> 
                                
                                <table border="0" align="center" width="180" cellpadding="0" cellspacing="0" bgcolor="00b5bc" style="margin-bottom:20px;">
                                    <tr>
                                        <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="color: #ffffff; font-size: 14px; font-family: 'Poppins', Calibri, sans-serif; line-height: 22px; letter-spacing: 2px;">
                                            <!-- main section button -->

                                            <div style="line-height: 22px;">
                                                <a href="{{$url}}" style="color: #ffffff; text-decoration: none;">RESET PASSWORD</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                                    </tr>
                                </table>
                                <p style="line-height: 24px;margin-bottom:15px;">
                                    If you did not request a password reset, please ignore this email or let us know at <span>{{ $contactEmail }}</span>
                                    </p>
                                <p style="line-height: 24px">
                                    Sincerely,</br>
                                    The {{ Config::get('app.name') }} team
                                </p> 
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
<!-- end section -->
@stop