<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Theme;

class ThemeMedium extends Model
{
    use HasFactory,SoftDeletes;

    public $table = 'theme_media';

    protected $fillable = [
        'theme_id',
        'image',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function theme()
     {
        return $this->belongsTo(Theme::class, 'theme_id');
     }

     protected function serializeDate(DateTimeInterface $date)
      {
            return $date->format('Y-m-d H:i:s');
      }
}
