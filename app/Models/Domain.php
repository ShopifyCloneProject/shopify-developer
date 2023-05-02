<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Domain extends Model
{
    use HasFactory,SoftDeletes;
    
    public $table = 'domains';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'appname',
        'appurl',
        'authurl',
        'call_app_url',
        'front_app_url',
        'db_connection',
        'db_host',
        'db_port',
        'db_database',
        'db_username',
        'db_password',
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'cashfree_return_url',
        'cashfree_notify_url',
        'razorpay_callback_url',
        'instamojo_callback_url',
        'paytm_callback_url',
        'instamojo_payment_request',
        'instamojo_webhook',
        'default_country',
        'default_currency',
        'order_start_number',
        'display_order_limit',
        'per_page',
        'search_user_limit',
        'search_product_limit',
        'shipment_order_number',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    public function user()
     {
        return $this->belongsTo(User::class, 'user_id');
     }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
