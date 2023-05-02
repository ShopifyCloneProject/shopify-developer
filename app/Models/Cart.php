<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Cart;
use App\Models\User;
use App\Models\CartDetail;

class Cart extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'carts';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'mac_id',
        'payment_status',
        'addresses_id',
        'shipping_address_id',
        'discount_code',
        'discount_amount',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function cart_detail()
    {
        return $this->hasMany(CartDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class,'addresses_id','id');
    }

    public function shippingaddress()
    {
        return $this->belongsTo(Address::class,'shipping_address_id','id');
    }
}
