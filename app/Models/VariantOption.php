<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VariantOption extends Model
{
    use SoftDeletes,HasFactory;

    public const STATUS_RADIO = [
        '1' => 'Active',
        '0' => 'In Active',
    ];

    public $table = 'variant_options';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'options',
        'status',
        'variant_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
