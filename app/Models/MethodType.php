<?php

namespace App\Models;


use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MethodType extends Model
{
    use HasFactory;

    public $table = 'method_types';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'payment_method_id',
        'payment_type_id',
        'is_enabled',
        'order',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
