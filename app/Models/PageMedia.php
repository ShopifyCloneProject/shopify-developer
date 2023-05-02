<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Config;

class PageMedia extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'pagemedias';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'src',
        'src_alt_text',
        'order',
        'mainmenu_id',
        'align',
        'text1',
        'text2',
        'url',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = ['imagedata'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getImagedataAttribute()
    {
        $data= [];
        if($this->attributes['src'] != null)
        {
            $data[0]['id'] = $this->attributes['id'];
            
            switch ($this->attributes['section_id']) {
                case 2:
                    $imagefolder = 'slider';
                    $imagesize = 1920;
                    break;
                 case 3:
                    $imagefolder = 'accessories';
                     $imagesize = 570;
                    break;
                case 4:
                    $imagefolder = 'companylogo';
                    $imagesize = 240;
                    break;
                 case 6:
                    $imagefolder = 'trends';
                    $imagesize = 1755;
                    break;
                default:
                    $imagefolder = 'slider';
                    $imagesize = 1920;
                    break;
            }
            $data[0]['imageurl'] = '/storage/'.Config::get('client_id').'/'.$imagefolder.'/'.$imagesize.'/'.$this->attributes['src'];
        }
        return $data;
    }

}
