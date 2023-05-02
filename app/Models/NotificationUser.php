<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationUser extends Model
{
    use HasFactory,SoftDeletes;

    public $table = 'user_notifications';

    protected $fillable = [
        'user_id',
        'notifications_id',
        'email_subject',
        'email_template',
        'sms_template',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function notifications()
    {
        return $this->belongsTo(Notification::class,'notifications_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
