<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageOption extends Model
{
    use HasFactory;

    public $table = 'page_options';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'option_name',
        'option_value',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
