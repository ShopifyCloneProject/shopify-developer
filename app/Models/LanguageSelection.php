<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Language;

class LanguageSelection extends Model
{
    use HasFactory,SoftDeletes;

    public $table = 'language_selections';

    protected $fillable = [
        'user_id',
        'language_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user()
     {
        return $this->belongsTo(User::class, 'user_id');
     }

     public function language()
     {
        return $this->belongsTo(Language::class, 'language_id');
     }

     protected function serializeDate(DateTimeInterface $date)
      {
            return $date->format('Y-m-d H:i:s');
      }
}
