<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory,SoftDeletes;

    public $table = 'notifications';

    public const folderPath = "views/email/notifications";

    public const STATUS_RADIO = [
        '1' => 'Active',
        '0' => 'In Active',
    ];
    
    protected $fillable = [
        'title',
        'description',
        'category',
        'options',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const Category =[
        'Orders',
        'Shipping',
        'Local delivery',
        'Local pickup',
        'Customer',
        'Email marketing',
        'Returns',
        'Marketing',
        'Staff order notifications',
        'Webhooks'
    ];

    public const Options =[
        'Normal',
        'Text',
        'Checkbox',
        'Radio'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
