<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ThemeMedium;

class Theme extends Model
{
    use HasFactory,SoftDeletes;

    public $table = 'themes';

    protected $fillable = [
        'name',
        'image',
        'themeurl',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function theme_medias()
    {
        return $this->hasMany(ThemeMedium::class,'theme_id','id');
    }

    public function theme_selections()
    {
        return $this->hasMany(ThemeSelection::class,'theme_id','id');
    }
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
