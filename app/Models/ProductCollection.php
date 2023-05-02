<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

class ProductCollection extends Pivot
{
    public $table = 'product_collection';

    protected $fillable = [
        'product_id',
        'collection_id',
        'order'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

   

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}	
