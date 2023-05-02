<?php

namespace App\Services;

use Illuminate\Http\Request;

use App\Http\Controllers\Traits\ApiResponser;
use App\Models\User;
use App\Models\Notification;

use App\Mail\MediaUploadSuccess;
use App\Mail\SendInvoice;
use App\Mail\SendAdminInvoice;
use App\Mail\WelcomeMail;
use App\Mail\OtpVerifyMail;
use App\Mail\OrderConfirmation;
use App\Mail\OrderEdited;
use App\Mail\OrderInvoice;
use App\Mail\OrderCancelled;
use App\Mail\OrderRefund;
use App\Mail\DraftOrderInvoice;
use App\Mail\AbandonedPosCheckout;
use App\Mail\AbandonedCheckout;
use App\Mail\PosAndMobileReceipt;
use App\Mail\PosExchangeReceipt;
use App\Mail\PosExchangeV2Receipt;
use App\Mail\GiftCardCreated;
use App\Mail\PaymentError;
use App\Mail\PendingPaymentError;
use App\Mail\PendingPaymentSuccess;
use App\Mail\FulfillmentRequest;
use App\Mail\ShippingConfirmation;
use App\Mail\ShippingUpdate;
use App\Mail\OutForDelivery;
use App\Mail\Delivered;
use App\Mail\LocalOrderOutForDelivery;
use App\Mail\LocalOrderDelivered;
use App\Mail\LocalOrderMissedDelivery;
use App\Mail\ReadyForPickup;
use App\Mail\PickedUp;
use App\Mail\CustomerAccountInvite;
use App\Mail\CustomerAccountWelcome;
use App\Mail\CustomerAccountPasswordReset;
use App\Mail\ContactCustomer;
use App\Mail\CustomerMarketingConfirmation;
use App\Mail\ReturnInstructionsWithLabelAndTracking;
use App\Mail\ReturnLabelOnly;
use Auth;
use Session;
use Mail;
use Config;
use Storage;
use PDF;

class EmailService
{
    use ApiResponser;
    protected $mail_username;
    public function __construct()
    {
        $this->mail_username = Config::get('mail.mailers.smtp.username');
    }

    // send plan change request mail
    public function sendMediaUploadSuccess($data)
    {
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                    Mail::to($data['email'])->send(new MediaUploadSuccess($data));
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    // send invoice mail
    public function sendInvoiceMail($data)
    {
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                /*$objNotification = Notification::whereId(1)->first();
                if($objNotification->email)
                {*/
                    Mail::to($data['email'])->send(new SendInvoice($data));
                /*}*/
            }   
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    // send admin invoice mail
    public function sendAdminInvoiceMail($data)
    {
        try{
            if($this->mail_username != '' || $this->mail_username != null){
               /* $objNotification = Notification::whereId(1)->first();
                if($objNotification->email)
                {*/
                    Mail::to($data['adminemail'])->send(new SendAdminInvoice($data));
                /*}*/
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }    
    }

    // send admin invoice mail
    public function sendWelcomeMail($data)
    {
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                /*$objNotification = Notification::whereId(1)->first();
                if($objNotification->email)
                {*/
                    Mail::to($data['email'])->send(new WelcomeMail($data));
                /*}*/
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function sendOtpVerifyMail($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                Mail::to($data['email'])->send(new OtpVerifyMail($data));
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function orderConfirmation($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(1)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new OrderConfirmation($data));   
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function orderEdited($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(2)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new OrderEdited($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function orderInvoice($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(3)->first();
                if($objNotification->email)
                {
                    $fileName = $data['orderNumber'].'-invoice.pdf';
                    $invoicePath = public_path('/storage/'.$data['client_id'].'/invoice/'.$fileName);
                    if(!file_exists($invoicePath))
                    {
                        $pdf = PDF::loadView('client.invoice', compact('data'));
                        Storage::put('public/'.$data['client_id'].'/invoice/'.$fileName, $pdf->output());
                    }
                    $objTemp['path'] = $invoicePath;
                    $objTemp['fileName'] = $fileName;
                    $data['invoicePath'][] = $objTemp;

                    Mail::to($data['email'])->send(new OrderInvoice($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function orderCancelled($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(4)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new OrderCancelled($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function orderRefund($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(5)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new OrderRefund($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function draftorderInvoice($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(6)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new DraftOrderInvoice($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function abandonedposCheckout($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(7)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new AbandonedPosCheckout($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function abandonedCheckout($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(8)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new AbandonedCheckout($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function posandmobileReceipt($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(9)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new PosAndMobileReceipt($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function posexchangeReceipt($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(10)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new PosExchangeReceipt($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function posexchangev2Receipt($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(11)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new PosExchangeV2Receipt($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function giftcardCreated($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(12)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new GiftCardCreated($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function paymentError($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(13)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new PaymentError($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function pendingpaymentError($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(14)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new PendingPaymentError($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function pendingpaymentSuccess($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(15)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new PendingPaymentSuccess($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function fulfillmentRequest($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(16)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new FulfillmentRequest($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function shippingConfirmation($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(17)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new ShippingConfirmation($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function shippingUpdate($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(18)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new ShippingUpdate($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function outforDelivery($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(19)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new OutForDelivery($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function delivered($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(20)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new Delivered($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function localorderoutforDelivery($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(21)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new LocalOrderOutForDelivery($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function localorderDelivered($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(22)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new LocalOrderDelivered($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function localordermisseddelivery($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(23)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new LocalOrderMissedDelivery($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function readyforPickup($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(24)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new ReadyForPickup($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function pickedUp($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(25)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new PickedUp($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function customeraccountInvite($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(26)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new CustomerAccountInvite($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function customeraccountWelcome($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(27)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new CustomerAccountWelcome($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function customeraccountpasswordReset($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(28)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new CustomerAccountPasswordReset($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function contactCustomer($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(29)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new ContactCustomer($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function customermarketingConfirmation($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(30)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new CustomerMarketingConfirmation($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function returninstructionswithlabelandTracking($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(31)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new ReturnInstructionsWithLabelAndTracking($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function returnlabelOnly($data){
        try{
            if($this->mail_username != '' || $this->mail_username != null){
                $objNotification = Notification::whereId(32)->first();
                if($objNotification->email)
                {
                    Mail::to($data['email'])->send(new ReturnLabelOnly($data));
                }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }
}
