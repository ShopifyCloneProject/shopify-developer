<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExchangeMedium extends Model
{
    use HasFactory,SoftDeletes;

    public $table = 'exchange_media';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'client_id',
        'order_id',
        'product_id',
        'product_variant_options_id',
        'src',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getSrcAttribute()
    {
        return "/storage/".$this->attributes['client_id']."/exchangeorder/".$this->attributes['product_id']."/". $this->attributes['src'];
    }
}
