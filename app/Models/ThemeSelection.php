<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Theme;

class ThemeSelection extends Model
{
    use HasFactory,SoftDeletes;

    public $table = 'theme_selections';

    protected $fillable = [
        'user_id',
        'theme_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user()
     {
        return $this->belongsTo(User::class, 'user_id');
     }

     public function theme()
     {
        return $this->belongsTo(Theme::class, 'theme_id');
     }

     protected function serializeDate(DateTimeInterface $date)
      {
            return $date->format('Y-m-d H:i:s');
      }
}
