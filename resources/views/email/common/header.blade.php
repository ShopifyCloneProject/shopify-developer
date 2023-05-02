<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
   <head>
      <meta charset="utf-8">
      <meta name="x-apple-disable-message-reformatting">
      <meta http-equiv="x-ua-compatible" content="ie=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
      <!--[if mso]>
      <xml>
         <o:OfficeDocumentSettings>
            <o:PixelsPerInch>96</o:PixelsPerInch>
         </o:OfficeDocumentSettings>
      </xml>
      <style>
         td,th,div,p,a,h1,h2,h3,h4,h5,h6 {font-family: "Segoe UI", sans-serif; mso-line-height-rule: exactly;}
      </style>
      <![endif]-->
      <link href="https://fonts.googleapis.com/css?family=Montserrat:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700" rel="stylesheet" media="screen">
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
                  <table class="sm-w-full" style="font-family: 'Montserrat',Arial,sans-serif; width: 600px;" width="600" cellpadding="0" cellspacing="0" role="presentation">
                     <tr>
                        <td class="sm-py-32 sm-px-24" style="font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; padding: 48px; text-align: center;" align="center">
                           <a href="{{ Config::get('app.url') }}">
                            @if(isset($globalSettings['strLogo']))  
                           <img src="{{ Config::get('app.url') }}{{ $globalSettings['strLogo'] }}" width="155" alt="Vuexy Admin" style="border: 0; max-width: 100%; line-height: 100%; vertical-align: middle;">
                           </a>
                           @endif
                        </td>
                     </tr>