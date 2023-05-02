<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Illuminate\Auth\Notifications\ResetPassword;
use App\Models\User;

class MailResetPasswordNotification extends ResetPassword
{
    use Queueable;
    private $email;
    /**
    * Create a new notification instance.
    *
    * @return void
    */
    public function __construct($token, $email)
    {
        parent::__construct($token);
        $this->email = $email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {   
        $isAdmin = User::where('email', $this->email)->where('role_id', 1)->first();
        if($isAdmin){
            $link =  config('app.url')."/admin/password/reset/".$this->token.'?email='.urlencode($this->email);
        } else{
            $link =  config('app.url')."/password/reset/".$this->token.'?email='.urlencode($this->email);
        }

        return (new MailMessage)->subject( 'Reset Password Notification' )->view('email.forgot_password', ['url' => $link]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
