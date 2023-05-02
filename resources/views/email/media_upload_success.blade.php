@extends('email.template')
@section('email.main')
<tr>
   <td align="center" class="sm-px-24" style="font-family: 'Montserrat',Arial,sans-serif;">
      <table style="font-family: 'Montserrat',Arial,sans-serif; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
         <tr>
            <td class="sm-px-24" style="--bg-opacity: 1; background-color: #ffffff; background-color: rgba(255, 255, 255, var(--bg-opacity)); border-radius: 4px; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; font-size: 14px; line-height: 24px; padding: 48px; text-align: left; --text-opacity: 1; color: #626262; color: rgba(98, 98, 98, var(--text-opacity));" bgcolor="rgba(255, 255, 255, var(--bg-opacity))" align="left">
               <p style="font-weight: 600; font-size: 18px; margin-bottom: 0;">Hey</p>
               <p style="font-weight: 700; font-size: 20px; margin-top: 0; --text-opacity: 1; color: #ff5850; color: rgba(255, 88, 80, var(--text-opacity));">{{$data['fname']}}</p>
               <p align="center" style="font-size: 18px; line-height: 24px; margin-top: 50px; margin-bottom: 20px;--text-opacity: 1; color: #626262; color: rgba(98, 98, 98, var(--text-opacity));">
                  Products image uploaded successfully.
               </p>
               <p style="font-size: 14px; line-height: 24px; margin-top: 6px; margin-bottom: 20px;">
                  Cheers,
                  <br>The {{ Config::get('app.name') }} Team
               </p>
            </td>
         </tr>
      </table>
   </td>
</tr>
@stop