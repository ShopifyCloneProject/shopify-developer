<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderLocation extends Model
{
    use SoftDeletes,HasFactory;

    public const STATUS_RADIO = [
        '1' => 'Shipping',
        '0' => 'Billing',
    ];

    public $table = 'order_locations';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'order_id',
        'user_id',
        'first_name',
        'last_name',
        'location_name',
        'company_name',
        'address',
        'address_2',
        'phone_code',
        'mobile',
        'status',
        'postal_code',
        'city_name',
        'state_id',
        'country_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends=[
        'state', 'country', 'short_code', 'phone_code'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getStateAttribute()
    {
        return $this->belongsTo(State::class, 'state_id')->value('name');
    }

    public function getCountryAttribute()
    {
        return $this->belongsTo(Country::class, 'country_id')->value('name');
    }

    public function getShortCodeAttribute()
    {
        return $this->belongsTo(Country::class, 'country_id')->value('short_code');
    }

    public function getPhoneCodeAttribute()
    {
        return $this->belongsTo(Country::class, 'country_id')->value('phone_code');
    }
}
