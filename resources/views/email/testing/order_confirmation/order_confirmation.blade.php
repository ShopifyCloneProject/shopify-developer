@extends('email.template') 
@section('email.main') 
<tr>
    <td align="center" class="sm-px-24" style="font-family: 'Montserrat',Arial,sans-serif;">
       <table style="font-family: 'Montserrat',Arial,sans-serif; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
          <tr>
             <td class="sm-px-24" style="--bg-opacity: 1; background-color: #ffffff; background-color: rgba(255, 255, 255, var(--bg-opacity)); border-radius: 4px; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; font-size: 14px; line-height: 24px; padding: 48px; text-align: left; --text-opacity: 1; color: #626262; color: rgba(98, 98, 98, var(--text-opacity));" bgcolor="rgba(255, 255, 255, var(--bg-opacity))" align="left">
                <p style="font-weight: 600; font-size: 14px; margin-bottom: 0;">Hey, {{$data['fullName']}}!</p>
                <p style="margin: 0 0 24px;">Thank You For Your Order!</p>
                <table style="font-family: 'Montserrat',Arial,sans-serif; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                   <tr>
                      <td style="font-family: 'Montserrat',Arial,sans-serif;">
                         <h3 style="font-size: 12px; margin-top: 0; text-align: left;">Order Number: <span style="font-weight: 700;">#{{$data['orderNumber']}}</span></h3>
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
                              <td width="65%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;">
                                  Order Confirmation #
                              </td>
                              <td width="35%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;">
                                  {{$data['orderNumber']}}
                              </td>
                          	</tr>
                          	<tr>
                              <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">
                                  Purchased Item ({{count($data['products'])}})
                              </td>
                              <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">
                                  {{ $data['currencySymbol'] }} {{ $data['subTotal'] }}
                              </td>
                          	</tr>
                          	<tr>
                              <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 5px 10px;">
                                  Shipping + Handling
                              </td>
                              <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 5px 10px;">
                                  {{ $data['currencySymbol'] }} {{ $data['shippingCost'] }}
                              </td>
                          	</tr>
                          	<tr>
                              <td width="65%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 5px 10px;">
                                  Sales Tax
                              </td>
                              <td width="35%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 5px 10px;">
                                  {{ $data['currencySymbol'] }} {{ $data['taxes'] }}
                              </td>
                          	</tr>
                           @if($data['discount'] > 0)
                           <tr>
                              <td width="65%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 5px 10px;">
                                  Discount ({{ $data['discountCode'] }})
                              </td>
                              <td width="35%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 5px 10px;">
                                  {{ $data['currencySymbol'] }} {{ $data['discount'] }}
                              </td>
                           </tr>
                           @endif
	                        <tr>
	                              <td width="65%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 600; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;">
	                                  TOTAL
	                              </td>
	                              <td width="35%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 600; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;">
	                                  {{ $data['currencySymbol'] }} {{$data['total']}}
	                              </td>
	                        </tr>
	                         <tr>
	                         	<td width="65%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 5px 10px;">
                                  Amount Paid
                              </td>
                              <td width="35%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 5px 10px;">
                                  {{ $data['currencySymbol'] }} {{$data['total']}}
                              </td>
	                         </tr>
                         </table>
                      </td>
                   </tr>
                </table>
                <p style="font-size: 14px; line-height: 24px; margin-top: 6px; padding-top: 12px;margin-bottom: 20px;border-top: 1px solid #ddd;font-weight: 700;">
                   Thank you for shopping with {{ Config::get('app.name') }}
                </p>
             </td>
          </tr>
       </table>
    </td>
</tr>
@endsection