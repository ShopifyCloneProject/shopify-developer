<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PaymentDetail extends Model
{
    use SoftDeletes,HasFactory;

    public const STATUS_RADIO = [
        '1' => 'Active',
        '0' => 'In Active',
    ];

    public $table = 'payment_details';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'payment_method_id',
        'description',
        'app_key',
        'app_secret',
        'industry_type',
        'website',
        'is_testmode',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
