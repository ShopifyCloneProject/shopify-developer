@extends('email.template') 
@section('email.main') 
<tr>
    <td align="center" class="sm-px-24" style="font-family: 'Montserrat',Arial,sans-serif;">
       <table style="font-family: 'Montserrat',Arial,sans-serif; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
          <tr>
             <td class="sm-px-24" style="--bg-opacity: 1; background-color: #ffffff; background-color: rgba(255, 255, 255, var(--bg-opacity)); border-radius: 4px; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; font-size: 14px; line-height: 24px; padding: 48px; text-align: left; --text-opacity: 1; color: #626262; color: rgba(98, 98, 98, var(--text-opacity));" bgcolor="rgba(255, 255, 255, var(--bg-opacity))" align="left">
                <p style="font-weight: 600; font-size: 14px; margin-bottom: 0;">Hey, {{$data['fullName']}}!</p>
                <p style="margin: 0 0 24px;">I hope you’re well. Please see attached invoice number {{ $data['orderNumber']}} for {{ Config::get('app.name') }} products. Don’t hesitate to reach out if you have any questions.</p>
                <p style="font-size: 14px; line-height: 24px; margin-top: 6px; padding-top: 12px;margin-bottom: 20px;border-top: 1px solid #ddd;font-weight: 700;">
                   Thank you for shopping with {{ Config::get('app.name') }}
                </p>
             </td>
          </tr>
       </table>
    </td>
</tr>
 @endsection