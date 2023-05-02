<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDetail extends Model
{
    use HasFactory,SoftDeletes;

    public $table = 'user_details';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'bio',
        'birth_date',
        'country_id',
        'website',
        'phone',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    public function user()
     {
        return $this->belongsTo(User::class, 'user_id');
     }
     
    public function country()
     {
        return $this->belongsTo(Country::class, 'country_id');
     }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
