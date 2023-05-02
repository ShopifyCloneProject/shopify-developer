<?php

namespace App\Models;

use \DateTimeInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use BinaryCabin\LaravelUUID\Traits\HasUUID;

use Config;

class Order extends Model
{
    use SoftDeletes, HasFactory, HasUUID;

    public $table = 'orders';
    protected $uuidFieldName = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public const SOURCE_RADIO = [
        'WEB' => 'Web',
        'APP' => 'App',
    ];

    public const RISK_LEVEL_RADIO = [
        '0' => 'Low',
        '1' => 'High',
    ];

    public const FULFILLMENT_STATUS_SELECT = [
        'fulfilled' => 'Fulfilled',
        'unfulfilled' => 'Unfulfilled',
    ];

    public const PAYMENT_STATUS = [
        'authorized' => 'Authorized',
        'captured' => 'Captured',
        'expired' => 'Expired',
        'paid' => 'Paid',
        'partially_paid' => 'Partially Paid',
        'partially_refunded' => 'Partially Refunded',
        'pending' => 'Pending',
        'refunded' => 'Refunded',
        'unpaid' => 'Unpaid',
        'voided' => 'Voided',
        'failed' => 'Failed',
        'exchanged' => 'Exchanged'
    ];

    public const STATUS = [
        'open' => 'Open',
        'archived' => 'Archived',
        'canceled' => 'Canceled',
        'any' => 'Any'
    ];

    protected $dates = [
        'paid_at',
        'fulfilled_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'order_nr',
        'currency_id',
        'shipping_method_id',
        'gateway',
        'user_id',
        'email',
        'mobile',
        'billing_address_id',
        'shipping_address_id',
        'payment_method_id',
        'paid_at',
        'fulfillment_status',
        'fulfilled_at',
        'accepts_marketing',
        'sub_total',
        'shipping_cost',
        'taxes',
        'total',
        'financial_status',
        'status',
        'discount_code',
        'discount_amount',
        'risk_level',
        'source',
        'tax_round',
        'shipping_round',
        'tax_1_name',
        'tax_1_value',
        'tax_2_name',
        'tax_2_value',
        'tax_3_name',
        'tax_3_value',
        'tax_4_name',
        'tax_4_value',
        'tax_5_name',
        'tax_5_value',
        'receipt_number',
        'note',
        'flash_status',
        'financial_status_id',
        'parent_order_id',
        'admin_approve',
        'country_tax_percentage',
        'state_tax_percentage',
        'state_text',
        'state_tax_additional',
        'shipping_rate_id',
        'rate_status',
        'conditions',
        'weight_or_price',
        'min',
        'max',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = ['currency_symbol'];

    public function financial_status()
    {
        return $this->belongsTo(OrderFinancialStatus::class, 'financial_status_id');
    }

    public function getCurrencySymbolAttribute()
    {
        $symbol = config('globalSettings')['CURRECNY_SYMBOL'];
        $objCurrency = Currency::whereId($this->attributes['currency_id'])->first();
        if(!empty($objCurrency))
        {
            $symbol = $objCurrency->symbol;
        }
        return $symbol;
    }

    public function getPaidAtAttribute()
    {
        $value = $this->attributes['paid_at'];
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
        return $value;

    }

   

    public function getFulfilledAtAttribute()
    {
        $value = $this->attributes['fulfilled_at'];
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
        return $value;
    }

    

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

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function shipping_method()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function billing_address()
    {
        return $this->belongsTo(OrderLocation::class, 'billing_address_id');
    }

    public function shipping_address()
    {
        return $this->belongsTo(OrderLocation::class, 'shipping_address_id');
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function order_products()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function refund_products()
    {
        return $this->hasMany(Refundproduct::class);
    }
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
