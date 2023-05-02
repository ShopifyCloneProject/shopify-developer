<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GiftCardVendor extends Model
{
    use SoftDeletes,HasFactory;

    public $table = 'gift_card_vendors';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'gift_card_id',
        'vendor_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function gift_card()
    {
        return $this->belongsTo(Product::class, 'gift_card_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
