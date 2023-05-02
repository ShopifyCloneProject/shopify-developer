<?php

namespace App\Models;

use \DateTimeInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Config;

class Shipping extends Model
{
    use HasFactory,SoftDeletes;

    public $table = 'shippings';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'order_id',
        'shipment_order_number',
        'user_id',
        'admin_approve',
        'pickup_id',
        'title',
        'quantity',
        'selling_price',
        'discount',
        'tax',
        'shipping_charges',
        'giftwrap_charges',
        'transaction_charges',
        'total_discount',
        'sub_total',
        'length_type_id',
        'length',
        'width_type_id',
        'width',
        'height_type_id',
        'height',
        'weight_type_id',
        'weight',
        'shipping_method_id',
        'courier_id',
        'rate_data',
        'parent_shipping_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'display_created_at'
    ];

    public function getDisplayCreatedAtAttribute($value)
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

    public function weight_type()
    {
        return $this->belongsTo(Weightmanage::class, 'weight_type_id');
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class, 'courier_id');
    }   

    public function shipping_method()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
     public function shipping_products()
    {
        return $this->hasMany(ShippingProduct::class, 'shipping_id');
    }

    public function shipment()
    {
        return $this->hasOne(Shipments::class, 'shipment_order_number' , 'shipment_order_number');
    }
    
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
