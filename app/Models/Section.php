<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{

    use SoftDeletes, HasFactory;

    public $table = 'sections';

    public const STATUS_RADIO = [
        '1' => 'Active',
        '0' => 'In active',
    ];


    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'columnname',
        'displaycolumnname',
        'status',
        'section',
        'relation',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        dd($date);
        if($date != null)
        {
            return $date->format('Y-m-d H:i:s');
        }
        return null;
    }

    public function relations()
    {
        return $this->hasMany(self::class, 'relation', 'id');
    }
}