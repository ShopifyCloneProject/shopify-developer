<?php

namespace App\Models;

use \DateTimeInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Str;

class Collection extends Model implements HasMedia
{
   use SoftDeletes, InteractsWithMedia, HasFactory, HasUUID;

    public $table = 'collections';
    protected $uuidFieldName = 'id';
    public $incrementing = false;
    protected $casts = ['id' => 'string'];
    protected $keyType = 'string';


    public const ONLINE_STORE_RADIO = [
        '1' => 'Active',
        '0' => 'Draft',
    ];

    public const STATUS_RADIO = [
        '1' => 'Active',
        '0' => 'In Active',
    ];

    public const COLLECTION_TYPE_RADIO = [
        '0' => 'Manual',
        '1' => 'Automated',
    ];

    public const DESCRIPTION_POSITION_RADIO = [
        '1' => 'Above',
        '0' => 'Below',
    ];

    public const CONDITIONS_TYPE_RADIO = [
        '0' => 'All Conditions',
        '1' => 'Any Conditions',
    ]; 

    public const PRODUCT_SHORT_TYPE = [
        '1' => 'Best selling',
        '2' => 'Product title A-Z',
        '3' => 'Product title Z-A',
        '4' => 'Highest price',
        '5' => 'Lowest price',
        '6' => 'Newest',
        '7' => 'Oldest',
        '8' => 'Manually',
    ];

    protected $appends = [
        'src',
    ];

    protected $dates = [
        'schedule_time',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'description',
        'collection_type',
        'conditions_type',
        'description_position',
        'seo_keywords',
        'seo_description',
        'status',
        'src_alt_text',
        'url',
        'online_store',
        'schedule_time',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function ($collection) {
            $collection->slug = $collection->createSlug($collection->title);
            $collection->save();
        });
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getSrcAttribute()
    {
        $file = $this->getMedia('url')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl();
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    // public function getScheduleTimeAttribute($value)
    // {
    //     return $value ? Carbon::createFromFormat('Y-m-d H:i a', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    // }

    // public function setScheduleTimeAttribute($value)
    // {
    //     $this->attributes['schedule_time'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i a') : null;
    // }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function collection_conditions(){
        return $this->hasMany(CollectionCondition::class, 'collection_id', 'id');
    }

    /**
    * The product that belong to the collection.
    */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_collection','collection_id', 'product_id')->using(new class extends Pivot {
                use HasUUID;
            });
    }

    private function createSlug($title){
        $slug = Str::slug($title);
        if (static::whereSlug($slug)->exists()) {
            $max = static::whereTitle($title)->latest('id')->first();
            if(!empty($max))
                $count = static::whereTitle($title)->count();
                return "{$slug}-".($count + 1);
            }
        return $slug;
    }
}
