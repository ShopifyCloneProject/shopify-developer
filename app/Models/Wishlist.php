<?php

namespace App\Models;

use \DateTimeInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use BinaryCabin\LaravelUUID\Traits\HasUUID;

class Wishlist extends Model
{
    use SoftDeletes, HasFactory, HasUUID;

    public $table = 'wishlists';
    protected $uuidFieldName = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'product_id',
        'user_id',
        'product_variant_options_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user(){
       return $this->belongsTo(User::class);
    }

    public function product(){
       return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
