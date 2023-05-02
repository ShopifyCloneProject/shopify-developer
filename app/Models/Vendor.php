<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use BinaryCabin\LaravelUUID\Traits\HasUUID;

class Vendor extends Model
{
    use SoftDeletes, HasFactory, HasUUID;

    public $table = 'vendors';
    protected $uuidFieldName = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public const STATUS_RADIO = [
        '1' => 'Active',
        '0' => 'In Active',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
