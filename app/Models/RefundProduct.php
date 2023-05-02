<?php

namespace App\Models;

use \DateTimeInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Config;

class RefundProduct extends Model
{
    use HasFactory,SoftDeletes;

    public $table = 'refund_products';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'order_id',
        'product_id',
        'user_id',
        'product_variant_options_id',
        'email',
        'phone',
        'quantity',
        'title',
        'price',
        'total',
        'src',
        'sku',
        'barcode',
        'weight_type_id',
        'weight',
        'length_type_id',
        'length',
        'width_type_id',
        'width',
        'height_type_id',
        'height',
        'hs_code',
        'special_price',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

     public function getCreatedAtAttribute($value)
    {
        $value = $this->attributes['created_at'];
        $objUserTimeZone = UserStore::where('user_id',Config::get('client_id'))->latest()->first();
        if(!empty($objUserTimeZone))
        {
            $objUserSelectedTimeZone = $objUserTimeZone->time_zone->timezone_value;
            $time = Carbon::parse($value, 'UTC')->setTimezone($objUserSelectedTimeZone);
            $value = $time->format('Y-m-d H:i:s');
            
        }
         if($value != null)
        {
        return Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format'));
        }
        return null;
    }

    public function getUpdatedAtAttribute($value)
    {
        $value = $this->attributes['updated_at'];
        $objUserTimeZone = UserStore::where('user_id',Config::get('client_id'))->latest()->first();
        if(!empty($objUserTimeZone))
        {
            $objUserSelectedTimeZone = $objUserTimeZone->time_zone->timezone_value;
            $time = Carbon::parse($value, 'UTC')->setTimezone($objUserSelectedTimeZone);
            $value = $time->format('Y-m-d H:i:s');
            
        }
         if($value != null)
        {
        return Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format'));
        }
        return null;
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product_variant_options()
    {
        return $this->belongsTo(ProductVariantOption::class, 'product_variant_options_id');
    }

    public function weight_type()
    {
        return $this->belongsTo(Weightmanage::class, 'weight_type_id');
    }

    public function length_type()
    {
        return $this->belongsTo(Dimension::class, 'length_type_id');
    }

    public function width_type()
    {
        return $this->belongsTo(Dimension::class, 'width_type_id');
    }

    public function height_type()
    {
        return $this->belongsTo(Dimension::class, 'height_type_id');
    }


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
