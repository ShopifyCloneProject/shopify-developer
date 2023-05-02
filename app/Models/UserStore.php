<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use BinaryCabin\LaravelUUID\Traits\HasUUID;

class UserStore extends Model
{
    use SoftDeletes, HasFactory, HasUUID;

    public $table = 'user_stores';
    protected $uuidFieldName = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public const UNIT_SYSTEM_SELECT = [
        '1' => 'Metric System',
        '2' => 'Imperial system',
    ];

    public const UNIT_WEIGHT_SELECT = [
        '1' => 'Kilogram (kg)',
        '2' => 'Gram (g)',
        '3' => 'Pound (lb)',
        '4' => 'Ounce (oz)',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'address_id',
        'timezone_id',
        'user_store_industry_id',
        'store_name',
        'store_contact_email',
        'sender_email',
        'company',
        'address',
        'address_2',
        'mobile',
        'city',
        'state_id',
        'country_id',
        'postal_code',
        'unit_system',
        'unit_weight',
        'prefix',
        'suffix',
        'currency_id',
        'symbol',
        'gst',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function time_zone()
    {
        return $this->belongsTo(TimeZone::class,'timezone_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
