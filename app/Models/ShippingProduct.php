<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingProduct extends Model
{
    use HasFactory,SoftDeletes;

    public $table = 'shipping_products';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'selling_price',
        'shipping_id',
        'product_id',
        'product_variant_options_id',
        'quantity',
        'sku',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function shipping()
    {
        return $this->belongsTo(Shipping::class, 'shipping_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
