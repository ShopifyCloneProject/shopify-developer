<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Services\EmailService;
use Auth;

class MediaUploadSuccessMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $emailService;
    public $user;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->emailService = new EmailService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $to_name = $this->user->name;
        $to_email = $this->user->email;
        $data = ["fname" => $to_name, 'email' => $to_email];
        
        $this->emailService->sendMediaUploadSuccess($data);
    }
}
