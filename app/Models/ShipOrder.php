<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipOrder extends Model
{
    use HasFactory,SoftDeletes;

    public const STATUS_RADIO = [
        '1' => 'Active',
        '0' => 'In Active',
    ];

    public $table = 'ship_orders';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'filter',
        'description',
        'shipping_method_id',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function shipping_method()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
