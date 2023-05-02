<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CollectionCondition extends Model
{
    use SoftDeletes,HasFactory;

    public const STATUS_RADIO = [
        '0' => 'Model Use',
        '1' => 'Model not use',
    ];

    public $table = 'collection_conditions';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'model_name',
        'model_ref',
        'status',
        'value',
        'collection_title_id',
        'condition_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function collection_title()
    {
        return $this->belongsTo(ConditionTitle::class, 'collection_title_id');
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class, 'condition_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
