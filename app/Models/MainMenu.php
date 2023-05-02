<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;
class MainMenu extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'mainmenu';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'menuname',
        'setlink',
        'url',
        'category',
        'category_product_relation',
        'relation',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = ['categoryrelation'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getCategoryrelationAttribute(){
        $data = [];
        if($this->attributes['setlink'] == 'chooseoption'){
            if($this->attributes['category'] == 'collection')
            {
                return $this->hasOne('App\Models\Collection', 'id', 'category_product_relation')->select('id','title','slug')->first();
            }
            else if($this->attributes['category'] == 'product')
            {
                return $this->hasOne('App\Models\Product', 'id','category_product_relation')->select('id','title','slug')->first();
            }
        }
         return $data;  
    }

    public function relations()
    {
        return $this->hasMany(self::class, 'relation', 'id')->where('level',3)->with('thirdrelations')->orderBy('order');
    }

    public function allrelations()
    {
         return $this->hasMany(self::class, 'relation','id')->where('level',2)->with('relations')->orderBy('order');
    }

    public function thirdrelations()
    {
         return $this->hasMany(self::class, 'relation','id')->where('level',4)->orderBy('order');
    }

  
  
}
