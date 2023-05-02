<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EmailService;
use App\Models\Order;
use App\Models\User;
use Helper;


class TempMailController extends Controller
{
    protected $emailService;

    public function __construct(){
        $this->emailService = new EmailService;
    }

    public function orderMailCheck()
    {
        $data = $this->getOrderData('c2228940-46ef-11ed-a108-ff7d6a22fcd4');
        // $this->emailService->orderConfirmation($data);
        // $this->emailService->OrderInvoice($data);
        // $this->emailService->orderCancelled($data); 
        dd("Mail sent");
    }

    public function checkWeight()
    {
        dd(Helper::getCartWeight(Helper::getCartUserData()));
    }
} 
