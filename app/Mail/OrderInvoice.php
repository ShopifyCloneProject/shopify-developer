<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Notification;
use App\Models\NotificationUser;
Use Config;
Use Str;

class OrderInvoice extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->data;
        $userId = Config::get('client_id');
        $template_path = Notification::folderPath;
        $objNotification = Notification::where('title','Order invoice')->first();
        $objNotificationUser = NotificationUser::where(['user_id' => $userId,'notifications_id' => $objNotification->id])->first();
        $templatename = Str::slug($objNotification->title , "_");
        $dynamicTemplateName = $templatename;
        $dynamicSubject = $objNotification->title;
        if(!empty($objNotificationUser)){
            $dynamicSubject = $objNotificationUser->email_subject;
            $template_path = resource_path() . '/views/email/notifications/'.$dynamicTemplateName.'/'.$userId.'.blade.php';
            if(file_exists($template_path)){
            $dynamicTemplateName = $userId;
            }
        }
        $email = $this->from($data['email'])->subject($dynamicSubject)->view('email.notifications.'.$templatename.'.'. $dynamicTemplateName)->with('data', $data);
        if(count($data['invoicePath']) > 0) {
            foreach($data['invoicePath'] as $file) {
                $email->attach($file['path'], [
                    'as' => $file['fileName'], // If you want you can chnage original name to custom name      
                    'mime' => "application/pdf"
                ]);
            }
        }
        return $email;
    }
}
