<?php

namespace App\Models;

use \DateTimeInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use BinaryCabin\LaravelUUID\Traits\HasUUID;

class Payment extends Model
{
    use SoftDeletes, HasFactory, HasUUID;

    public $table = 'payments';
    protected $uuidFieldName = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'order_id',
        'payment_id',
        'amount',
        'payment_status',
        'data',
        'current_data',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = ['decode_data', 'decode_current_data'];

    public function getDecodeDataAttribute()
    {
        $data = [];
        if($this->attributes['data'] != null){
            $data = json_decode($this->attributes['data'],true);
        }
        return $data;
    }

    public function getDecodeCurrentDataAttribute()
    {
        $data = [];
        if($this->attributes['current_data'] != null){
            $data = json_decode($this->attributes['current_data'],true);
        }
        return $data;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
