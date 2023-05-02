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
                                    Welcome to <span style="color: #02b9b5;">{{ Config::get('app.name') }}!</span>
                                </div>

                                <p style="line-height: 24px; margin-bottom:15px;">Hello! {{ $data['name'] }}</p>
                                <p style="line-height: 24px;margin-bottom:15px;">
                                  Congratulations on starting your journey with {{ Config::get('app.name') }}!. We are thrilled to have you on board!
                                </p> 
                               
                                <p style="line-height: 24px;margin-bottom:15px;">
                                    Below are some quick links & info to get you started. If you have any questions, our Support Team is available at <span>{{ $contactEmail }}</span>
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