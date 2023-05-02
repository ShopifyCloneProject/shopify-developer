<?php

namespace App\Models;

use \DateTimeInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GiftCardIssue extends Model
{
    use SoftDeletes,HasFactory;

    public const ENABLED_RADIO = [
        '1' => 'True',
        '0' => 'False',
    ];

    public const STATUS_RADIO = [
        '1' => 'Enabled',
        '0' => 'Disabled',
    ];

    public const EXPIRATION_TYPE_RADIO = [
        '1' => 'No Expiry',
        '2' => 'set Expiry',
    ];

    public $table = 'gift_card_issues';

    protected $dates = [
        'date_issued',
        'expiration_date',
        'disabled_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'code',
        'status',
        'date_issued',
        'remaining_value',
        'initial_value',
        'expiration_type',
        'expiration_date',
        'note',
        'enabled',
        'disabled_at',
        'user_id',
        'gift_card_id',
        'currency_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getDateIssuedAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setDateIssuedAttribute($value)
    {
        $this->attributes['date_issued'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getExpirationDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setExpirationDateAttribute($value)
    {
        $this->attributes['expiration_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getDisabledAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setDisabledAtAttribute($value)
    {
        $this->attributes['disabled_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function gift_card()
    {
        return $this->belongsTo(Product::class, 'gift_card_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
