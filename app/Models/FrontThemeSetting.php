<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class FrontThemeSetting extends Model
{
    use HasFactory,SoftDeletes;

    public $table = 'theme_settings';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'facebook',
        'twitter',
        'gplus',
        'instagram',
        'vimeo',
        'linkedin',
        'pinterest',
        'youtube',
        'email',
        'sitename',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
