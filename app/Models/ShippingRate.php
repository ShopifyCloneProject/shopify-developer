<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingRate extends Model
{
    use HasFactory,SoftDeletes;

    public $table = 'shipping_rates';

    // public const PRODUCT_OR_COLLECTION_RADIO = [
    //     '1' => 'Product',
    //     '0' => 'Collection',
    // ];

    // public const STATUS_RADIO = [
    //     '1' => 'Enabled',
    //     '0' => 'Disabled',
    // ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'rate_status',
        'name',
        'price',
        'conditions',
        'weight_or_price',
        'min',
        'max',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = ['rateEditUrl'];

    public function getRateEditUrlAttribute()
    {
        $rateEditUrl = Route('admin.settings.shipping.edit-rates',['rateId'=>$this->attributes['id']]);
        return $rateEditUrl;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
