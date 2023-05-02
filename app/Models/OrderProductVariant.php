<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProductVariant extends Model
{
    use SoftDeletes,HasFactory;

    public $table = 'order_product_variants';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'order_detail_id',
        'product_variant_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function order_detail()
    {
        return $this->belongsTo(Order::class, 'order_detail_id');
    }

    public function product_variant()
    {
        return $this->belongsTo(ProductVariantOption::class, 'product_variant_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
