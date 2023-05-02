<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
      <meta name="x-apple-disable-message-reformatting">
      <meta http-equiv="x-ua-compatible" content="ie=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
      <style>
         .hover-underline:hover {
         text-decoration: underline !important;
         }
         @keyframes spin {
         to {
         transform: rotate(360deg);
         }
         }
         @keyframes ping {
         75%,
         100% {
         transform: scale(2);
         opacity: 0;
         }
         }
         @keyframes pulse {
         50% {
         opacity: .5;
         }
         }
         @keyframes bounce {
         0%,
         100% {
         transform: translateY(-25%);
         animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
         }
         50% {
         transform: none;
         animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
         }
         }
         @media (max-width: 600px) {
         .sm-px-24 {
         padding-left: 24px !important;
         padding-right: 24px !important;
         }
         .sm-py-32 {
         padding-top: 32px !important;
         padding-bottom: 32px !important;
         }
         .sm-w-full {
         width: 100% !important;
         }
         }
      </style>
   </head>
   <body style="margin: 0; padding: 0; width: 100%; word-break: break-word; -webkit-font-smoothing: antialiased;">
      <div style="display: none;">This is an invoice for your purchase on undefined. Please submit payment by undefined</div>
      <div role="article" aria-roledescription="email" aria-label="" lang="en">
         <table style="font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
               <td align="center" style="--bg-opacity: 1; background-color: #eceff1; background-color: rgba(236, 239, 241, var(--bg-opacity)); font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif;" bgcolor="rgba(236, 239, 241, var(--bg-opacity))">
                  <table class="sm-w-full" style="font-family: 'Montserrat',Arial,sans-serif;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                     <tr>
                        <td align="center" class="sm-px-24" style="font-family: 'Montserrat',Arial,sans-serif;">
                           <table style="font-family: 'Montserrat',Arial,sans-serif; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                              <tr>
                                 <td class="sm-px-24" style="--bg-opacity: 1; background-color: #ffffff; background-color: rgba(255, 255, 255, var(--bg-opacity)); border-radius: 4px; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; font-size: 14px; line-height: 24px; padding: 28px; text-align: left; --text-opacity: 1; color: #626262; color: rgba(98, 98, 98, var(--text-opacity));" bgcolor="rgba(255, 255, 255, var(--bg-opacity))" align="left">
                                    <h2 style="padding: 0 0 12px;font-weight: 700;border-bottom: 1px solid #ddd;text-align: center;">Invoice</h2>
                                    <table style="font-family: 'Montserrat',Arial,sans-serif; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                       <tr>
                                          <td style="font-family: 'Montserrat',Arial,sans-serif;border-bottom: 1px solid #ddd;width: 50%;">
                                             <h3 style="font-size: 12px; margin-top: 0; text-align: left;">Order ID: <span style="font-weight: 700;">#{{$data['orderId']}}</span></h3>
                                          </td>
                                          <td style="font-family: 'Montserrat',Arial,sans-serif;border-bottom: 1px solid #ddd;width: 50%;">
                                             <h3 style="font-weight: 700; font-size: 12px; margin-top: 0;">Order Placed On: <span style="font-weight: 700;">{{$data['orderDate']}}</span>
                                             </h3>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td style="font-family: 'Montserrat',Arial,sans-serif;width: 50%;">
                                             <h3 style="font-size: 12px; text-align: left;">Shipping Address:</h3>
                                          </td>
                                          <td style="font-family: 'Montserrat',Arial,sans-serif;width: 50%;">
                                              <h3 style="font-size: 12px; text-align: left;">Billing Address:</h3>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td style="font-family: 'Montserrat',Arial,sans-serif;border-bottom: 1px solid #ddd;width: 50%;padding-bottom: 10px;">
                                             <span style="font-size:13px; font-weight: bold;">{{ $data['shippingAddress']['first_name'] }}</span>
                                             @if($data['shippingAddress']['last_name'] != '')
                                                <span style="font-size:13px; font-weight: bold;"> {{ $data['shippingAddress']['last_name'] }}</span>
                                             @endif
                                             <br />
                                             <span style="font-size:12px;">{{ $data['shippingAddress']['address'] }}</span><br />
                                             @if($data['shippingAddress']['address_2'] != '')
                                                <span style="font-size:12px;">{{ $data['shippingAddress']['address_2'] }}</span><br />
                                             @endif
                                             <span style="font-size:12px;">{{ $data['shippingAddress']['city_name'] }} </span>
                                             <span style="font-size:12px;">{{ $data['shippingAddress']['postal_code'] }}</span>
                                             <span style="font-size:12px;">{{ $data['shippingAddress']['state'] }} </span><br />
                                             @if($data['shippingAddress']['mobile'] != '')
                                                <span style="font-size:12px;">{{ $data['shippingAddress']['mobile'] }}</span>
                                             @endif
                                          </td>
                                          <td style="font-family: 'Montserrat',Arial,sans-serif;border-bottom: 1px solid #ddd;width: 50%;padding-bottom: 10px;">
                                              <span style="font-size:13px; font-weight: bold;">{{ $data['billingAddress']['first_name'] }}</span>
                                             @if($data['billingAddress']['last_name'] != '')
                                                <span style="font-size:13px; font-weight: bold;"> {{ $data['billingAddress']['last_name'] }}</span>
                                             @endif
                                             <br />
                                             <span style="font-size:12px;">{{ $data['billingAddress']['address'] }}</span><br />
                                             @if($data['billingAddress']['address_2'] != '')
                                                <span style="font-size:12px;">{{ $data['billingAddress']['address_2'] }}</span><br />
                                             @endif
                                             <span style="font-size:12px;">{{ $data['billingAddress']['city_name'] }} </span>
                                             <span style="font-size:12px;">{{ $data['billingAddress']['postal_code'] }}</span>
                                              <span style="font-size:12px;">{{ $data['billingAddress']['state'] }} </span><br />
                                             @if($data['billingAddress']['mobile'] != '')
                                                <span style="font-size:12px;">{{ $data['billingAddress']['mobile'] }}</span>
                                             @endif
                                          </td>
                                       </tr>
                                       <tr>
                                          <td colspan="2" style="font-family: 'Montserrat',Arial,sans-serif;">
                                             <table style="font-family: 'Montserrat',Arial,sans-serif; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                                <tr>
                                                   <th align="left" style="padding-bottom: 8px;font-size: 13px;">
                                                      <p>Title</p>
                                                   </th>
                                                   <th align="left" style="padding-bottom: 8px;text-align: center;font-size: 13px;">
                                                      <p>Price</p>
                                                   </th>
                                                   <th align="left" style="padding-bottom: 8px;text-align: center;font-size: 13px;">
                                                      <p>Qty</p>
                                                   </th>
                                                   <th align="right" style="padding-bottom: 8px;font-size: 13px;">
                                                      <p>Total</p>
                                                   </th>
                                                </tr>
                                                @foreach($data['products'] as $key=>$product)
                                                <tr>
                                                   <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width: 60%;" width="80%">
                                                      <a href="{{ Config::get('app.url') }}/product/detail/{{$product['slug']}}" style="font-family:Arial;font-size:14px;font-weight:normal;font-style:normal;font-stretch:normal;line-height:20px;color:#212121;text-decoration:none!important;word-spacing:0.2em;">{{$product['title']}}</a>
                                                   </td>
                                                   <td align="right" style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; text-align: center; width: 15%;" width="15%">{{ $product['price'] }} </td>
                                                   <td align="right" style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; text-align: center; width: 10%;" width="10%">{{ $product['quantity'] }} </td>
                                                   <td align="right" style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; text-align: right; width: 15%;" width="15%">{{ $globalSettings['CURRECNY_SYMBOL'] }} {{ number_format($product['price'] * $product['quantity'], 2)}} </td>
                                                </tr>
                                                @endforeach
                                                <tr>
                                                   <td colspan="2" style="font-family: 'Montserrat',Arial,sans-serif;width: 50%;border-top: 1px solid #ddd;padding-top: 15px;" width="50%">
                                                      <p align="right" style="font-weight: 700; font-size: 14px; line-height: 24px; margin: 0; padding-right: 16px; text-align: right;">
                                                         Item(s) Total
                                                      </p>
                                                   </td>
                                                   <td colspan="2" style="font-family: 'Montserrat',Arial,sans-serif; width: 50%;border-top: 1px solid #ddd;padding-top: 15px;" width="50%">
                                                      <p align="right" style="font-weight: 700; font-size: 14px; line-height: 24px; margin: 0; text-align: right;">
                                                         {{ $globalSettings['CURRECNY_SYMBOL'] }} {{$data['total']}}
                                                      </p>
                                                   </td>
                                                </tr>
                                                <tr>
                                                   <td colspan="2" tyle="font-family: 'Montserrat',Arial,sans-serif;width: 50%;padding-bottom: 15px;" width="50%">
                                                      <p align="right" style="font-weight: 700; font-size: 14px; line-height: 24px; margin: 0; padding-right: 16px; text-align: right;">
                                                         Amount Paid
                                                      </p>
                                                   </td>
                                                   <td colspan="2" style="font-family: 'Montserrat',Arial,sans-serif; width: 50%;padding-bottom: 15px;" width="50%">
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
                  </table>
               </td>
            </tr>
         </table>
      </div>
   </body>
</html>