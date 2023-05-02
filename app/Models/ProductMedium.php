<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Config;

class ProductMedium extends Model
{
    use SoftDeletes,HasFactory;

    public $table = 'product_media';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'client_id',
        'product_id',
        'src',
        'src_alt_text',
        'is_default',
        'reorder',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

     protected $appends = [
        'image_src'
     ];

    public function getImageSrcAttribute()
    {
        $arrImageSize = explode(",", Config::get('site.imagesize'));
        $imageSrcData = ["assets/images/no-image.jpg"];
        if(file_exists(public_path("/storage/".$this->attributes['client_id']."/images/".$this->attributes['product_id']."/".$this->attributes['src'])))
        {
            $imageSrcData = ["/storage/".$this->attributes['client_id']."/images/".$this->attributes['product_id']."/".$this->attributes['src']];
        }
        
        foreach($arrImageSize as $key => $value)
        {
            if(file_exists(public_path("/storage/".$this->attributes['client_id']."/images/".$this->attributes['product_id']."/".$value."/".$this->attributes['src'])))
            {
                array_push($imageSrcData, "/storage/".$this->attributes['client_id']."/images/".$this->attributes['product_id']."/".$value."/".$this->attributes['src']);
            }
            else
            {
                array_push($imageSrcData,"/assets/images/no-image.jpg");
            }
        }  
        return $imageSrcData;
    }
   

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

   

}