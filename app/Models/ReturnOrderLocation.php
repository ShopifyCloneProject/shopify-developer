<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnOrderLocation extends Model
{
    use HasFactory,SoftDeletes;

    public const STATUS_RADIO = [
        '1' => 'Shipping',
        '0' => 'Billing',
    ];

    public $table = 'return_order_locations';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'order_id',
        'user_id',
        'location_name',
        'first_name',
        'last_name',
        'company_name',
        'email',
        'address',
        'address_2',
        'phone_code',
        'mobile',
        'status',
        'postal_code',
        'country_id',
        'state_id',
        'city_name',
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
