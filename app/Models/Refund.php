<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refund extends Model
{
   use SoftDeletes,HasFactory;

    public $table = 'refunds';


    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'order_id',
        'payment_id',
        'refund_id',
        'refund_status',
        'amount',
        'note',
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
