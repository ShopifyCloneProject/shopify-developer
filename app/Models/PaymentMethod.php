<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use SoftDeletes,HasFactory;

    public const STATUS_RADIO = [
        '1' => 'Active',
        '0' => 'Deactive',
    ];

    public $table = 'payment_methods';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function details()
    {
        return $this->hasOne(PaymentDetail::class);
    }

    public function types()
    {
        return $this->belongsToMany(PaymentType::class, 'method_types')->withPivot('is_enabled');
    }
}
