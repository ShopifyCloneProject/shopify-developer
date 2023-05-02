<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory,SoftDeletes;

    public $table = 'discounts';

    public const PRODUCT_OR_COLLECTION_RADIO = [
        '1' => 'Product',
        '0' => 'Collection',
    ];

    public const STATUS_RADIO = [
        '1' => 'Enabled',
        '0' => 'Disabled',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'code',
        'starting_date',
        'expiry_date',
        'expiry_type',
        'currency_id',
        'percentage_or_amount',
        'amount',
        'initial_value',
        'product_or_collection',
        'product_status',
        'status',
        'user_availability',
        'note',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
