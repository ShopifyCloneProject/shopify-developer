<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Config;

class AdminSetting extends Model
{
    use HasFactory,SoftDeletes;
    public $table = 'admin_settings';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'icon',
        'logo',
        'title',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getLogoAttribute()
    {   
        $logo = "/images/logo/logo.png";
        if($this->attributes['logo'] != null)
        {
            $path = '/storage/'.Config::get('client_id').'/logo/'.$this->attributes['logo'];
            if(file_exists(public_path($path)))
            {
                $logo = $path;  
            }
        }
        return $logo;
    }
     public function getIconAttribute()
    {   
        $icon = "/images/logo/favicon.ico";
        if($this->attributes['icon'] != null)
        {
            $path = '/storage/'.Config::get('client_id').'/icon/'.$this->attributes['icon'];
            if(file_exists(public_path($path)))
            {
                $icon = $path;  
            }
        }
        return $icon;
    }


}
