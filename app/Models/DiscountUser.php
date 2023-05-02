<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountUser extends Model
{
    use HasFactory,SoftDeletes ;

    public $table = 'discount_users';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'discount_id',
        'user_id',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // public function discount_user()
    // {
    //     return $this->hasMany(User::class,'id','user_id');
    // }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
