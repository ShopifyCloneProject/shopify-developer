<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountProduct extends Model
{
    use HasFactory,SoftDeletes;

    public $table = 'discount_products';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'discount_id',
        'product_id',
        'product_variant_options_id',
        'initial_value',
        'status',
        'currency_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

   

    public function products()
    {
        return $this->belongsTo(Product::class, 'id','product_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
