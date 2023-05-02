<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Config;

class VariantMedium extends Model
{
    use SoftDeletes,HasFactory;

    public $table = 'variant_media';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'client_id',
        'product_variant_id',
        'product_id',
        'src',
        'src_alt_text',
        'is_default',
        'reorder',
        'convert',
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
        $imageSrcData = ["/storage/".$this->attributes['client_id']."/images/".$this->attributes['product_id']."/".$this->attributes['src']];
        
        foreach($arrImageSize as $key => $value)
        {
            array_push($imageSrcData, "/storage/".$this->attributes['client_id']."/images/".$this->attributes['product_id']."/".$value."/".$this->attributes['src']);
        }   
        return $imageSrcData;
    }

    public function product_variant()
    {
        return $this->belongsTo(ProductVariantOption::class, 'product_variant_id');
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
