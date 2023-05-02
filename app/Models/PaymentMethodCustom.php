<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethodCustom extends Model
{
    use SoftDeletes;
    use HasFactory;

    public const CUSTOM_TYPE = [
        '0' => 'Cod',
        '1' => 'Bank Account',
        '2' => 'Money',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];  

    protected $fillable = [
        'user_id',
        'type',
        'name',
        'additional_details',
        'additional_instruction',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
