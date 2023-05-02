@extends('email.template')
@section('email.main')
<tr>
    <td align="center" class="sm-px-24" style="font-family: 'Montserrat',Arial,sans-serif;">
       <table style="font-family: 'Montserrat',Arial,sans-serif; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
          <tr>
             <td class="sm-px-24" style="--bg-opacity: 1; background-color: #ffffff; background-color: rgba(255, 255, 255, var(--bg-opacity)); border-radius: 4px; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; font-size: 14px; line-height: 24px; padding: 48px; text-align: left; --text-opacity: 1; color: #626262; color: rgba(98, 98, 98, var(--text-opacity));" bgcolor="rgba(255, 255, 255, var(--bg-opacity))" align="left">
                <p style="font-weight: 600; font-size: 14px; margin-bottom: 0;">Greeting!</p>
                <p style="margin: 0 0 24px;">You have received new order from {{$data['fname']}}.</p>
                <table style="font-family: 'Montserrat',Arial,sans-serif; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                   <tr>
                      <td style="font-family: 'Montserrat',Arial,sans-serif;">
                         <h3 style="font-size: 12px; margin-top: 0; text-align: left;">Order ID: <span style="font-weight: 700;">#{{$data['orderNumber']}}</span></h3>
                      </td>
                      <td style="font-family: 'Montserrat',Arial,sans-serif;">
                         <h3 style="font-weight: 700; font-size: 12px; margin-top: 0; text-align: right;">Order Placed On: <span style="font-weight: 700;">{{$data['orderDate']}}</span>
                         </h3>
                      </td>
                   </tr>
                   <tr>
                      <td colspan="2" style="font-family: 'Montserrat',Arial,sans-serif;">
                         <table style="font-family: 'Montserrat',Arial,sans-serif; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                            <tr>
                               <th align="left" style="padding-bottom: 8px;">
                                  <p>Title</p>
                               </th>
                               <th align="right" style="padding-bottom: 8px;">
                                  <p>Amount</p>
                               </th>
                            </tr>

                            @foreach($data['products'] as $key=>$product)
                            <tr>
                               <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width: 80%;" width="80%">
                                  <a href="{{ Config::get('app.url') }}/product/detail/{{$product['slug']}}" style="font-family:Arial;font-size:14px;font-weight:normal;font-style:normal;font-stretch:normal;line-height:20px;color:#212121;text-decoration:none!important;word-spacing:0.2em;">{{$product['title']}}</a>
                               </td>
                               <td align="right" style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; text-align: right; width: 20%;" width="20%">{{ $globalSettings['CURRECNY_SYMBOL'] }} {{ number_format($product['price'] * $product['quantity'], 2)}} </td>
                            </tr>
                            @endforeach
                            
                            <tr>
                               <td style="font-family: 'Montserrat',Arial,sans-serif;width: 80%;border-top: 1px solid #ddd;padding-top: 15px;" width="80%">
                                  <p align="right" style="font-weight: 700; font-size: 14px; line-height: 24px; margin: 0; padding-right: 16px; text-align: right;">
                                     Item(s) Total
                                  </p>
                               </td>
                               <td style="font-family: 'Montserrat',Arial,sans-serif; width: 20%;border-top: 1px solid #ddd;padding-top: 15px;" width="20%">
                                  <p align="right" style="font-weight: 700; font-size: 14px; line-height: 24px; margin: 0; text-align: right;">
                                     {{ $globalSettings['CURRECNY_SYMBOL'] }} {{$data['total']}}
                                  </p>
                               </td>
                            </tr>
                            <tr>
                               <td style="font-family: 'Montserrat',Arial,sans-serif;width: 80%;padding-bottom: 15px;" width="80%">
                                  <p align="right" style="font-weight: 700; font-size: 14px; line-height: 24px; margin: 0; padding-right: 16px; text-align: right;">
                                     Amount Paid
                                  </p>
                               </td>
                               <td style="font-family: 'Montserrat',Arial,sans-serif; width: 20%;padding-bottom: 15px;" width="20%">
                                  <p align="right" style="font-weight: 700; font-size: 14px; line-height: 24px; margin: 0; text-align: right;">
                                     {{ $globalSettings['CURRECNY_SYMBOL'] }} {{$data['total']}}
                                  </p>
                               </td>
                            </tr>
                         </table>
                      </td>
                   </tr>
                </table>
             </td>
          </tr>
       </table>
    </td>
</tr>
@stop