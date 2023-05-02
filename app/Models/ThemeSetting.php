<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Config;

class ThemeSetting extends Model
{
    use HasFactory,SoftDeletes;

    public const STATUS_RADIO = [
        '1' => 'Active',
        '0' => 'In Active',
    ];

    public $table = 'themepages';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'page',
        'sectionname',
        'status',
        'order',
        'logo',
        'icon',
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
        $logo = [];
        if($this->attributes['logo'] != null)
        {
            $logo[0]['id'] = $this->attributes['id'];
            $logo[0]['imageurl'] = '/storage/'.Config::get('client_id').'/logo/120/'.$this->attributes['logo'];
           
        }
        return $logo;
    }
     public function getIconAttribute()
    {   
        $icon = [];
        if($this->attributes['icon'] != null)
        {
            $icon[0]['id'] = $this->attributes['id'];
            $icon[0]['imageurl'] = '/storage/'.Config::get('client_id').'/icon/'.$this->attributes['icon'];
           
        }
        return $icon;
    }

    public function pagemedias()
    {
        return $this->hasMany(PageMedia::class,'section_id','id')->orderBy('order');
    }
}
