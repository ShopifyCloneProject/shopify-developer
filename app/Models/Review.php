<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Config;
use Carbon\Carbon;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'reviews';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'product_id',
        'title',
        'description',
        'star_rating',
        'approved',
    ];

    public function getCreatedAtAttribute($value)
    {
        $value = $this->attributes['created_at'];
        $objUserTimeZone = UserStore::where('user_id',Config::get('client_id'))->latest()->first();
        if(!empty($objUserTimeZone))
        {
            $objUserSelectedTimeZone = $objUserTimeZone->time_zone->timezone_value;
            $time = Carbon::parse($value, 'UTC')->setTimezone($objUserSelectedTimeZone);
            $value = $time->format('Y-m-d H:i:s');
            
        }
         if($value != null)
        {
        return Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format'));
        }
        return null;
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function media()
    {
        return $this->hasMany(ReviewImage::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
