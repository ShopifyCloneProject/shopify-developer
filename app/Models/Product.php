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
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\Wishlist;
use App\Models\InventoryStock;
use Auth;
use DB;
use Config;

class Product extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory, HasUUID;

    public $table = 'products';
    protected $uuidFieldName = 'id';
    public $incrementing = false;
    protected $casts = ['id' => 'string'];
    protected $keyType = 'string';

    public const STATUS_SELECT = [
        '0' => 'Draft',
        '1' => 'Active',
    ];

    public const IS_PRODUCT_CHARGE_RADIO = [
        '0' => 'No',
        '1' => 'Yes',
    ];

    public const IS_GIFT_CARD_RADIO = [
        '0' => 'Product',
        '1' => 'Gift Card',
    ];

    public const TRACK_SELECT = [
        '0' => 'Not Track',
        '1' => 'Track',
    ];

    protected $appends = [
        'original_price',
        'is_wishlist',
        'stock_status',
        'db_start_schedule_date',
        'db_schedule_time',
        'db_expiry_date'
    ];

    protected $dates = [
        'schedule_time',
        'expiry_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'slug',
        'description',
        'product_type_id',
        'vendor_id',
        'status',
        'schedule_time',
        'price',
        'compare_at_price',
        'cost_per_item',
        'is_product_charge',
        'sku',
        'barcode',
        'is_track',
        'is_continue_selling',
        'is_physical_product',
        'weight_type_id',
        'weight',
        'length_type_id',
        'length',
        'width_type_id',
        'width',
        'height_type_id',
        'height',
        'country_id',
        'hs_code',
        'min_order_limit',
        'max_order_limit',
        'is_cod_enabled',
        'is_size_chart_enabled',
        'is_special_product',
        'special_price',
        'start_schedule_date',
        'expiry_date',
        'special_product_status',
        'seo_title',
        'seo_description',
        'is_gift_card',
        'column',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function ($product) {
            $product->slug = $product->createSlug($product->title);
            $product->save();
        });
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function product_type()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function product_tags()
    {
        return $this->hasMany(ProductTag::class, 'product_id', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function weight_type()
    {
        return $this->belongsTo(Weightmanage::class, 'weight_type_id');
    }

    public function length_type()
    {
        return $this->belongsTo(Dimension::class, 'length_type_id');
    }

    public function width_type()
    {
        return $this->belongsTo(Dimension::class, 'width_type_id');
    }

    public function height_type()
    {
        return $this->belongsTo(Dimension::class, 'height_type_id');
    }

    public function product_variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function product_variant_options()
    {
        return $this->hasMany(ProductVariantOption::class, 'product_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function getDbExpiryDateAttribute()
    {
         if(isset($this->attributes['expiry_date']))
        {
            return $this->attributes['expiry_date'];
        }
        return null;
    }

    public function getExpiryDateAttribute()
    {
        $value = $this->attributes['expiry_date'];
        $objUserTimeZone = UserStore::where('user_id',Config::get('client_id'))->latest()->first();
        if(!empty($objUserTimeZone) && $value != null)
        {
            $objUserSelectedTimeZone = $objUserTimeZone->time_zone->timezone_value;
            $time = Carbon::parse($value, 'UTC')->setTimezone($objUserSelectedTimeZone);
            $value = $time->format('Y-m-d');
        }
        if($value != null)
        {
            return Carbon::createFromFormat('Y-m-d', $value)->format(Config::get('panel.date_format'));
        }
        return $value;
    }

    public function getDbStartScheduleDateAttribute()
    {
        if(isset($this->attributes['start_schedule_date']))
        {
            return $this->attributes['start_schedule_date'];
        }
        return null;
    }

    public function getStartScheduleDateAttribute()
    {
        $value = $this->attributes['start_schedule_date'];
        $objUserTimeZone = UserStore::where('user_id',Config::get('client_id'))->latest()->first();
        if(!empty($objUserTimeZone) && $value != null)
        {
            $objUserSelectedTimeZone = $objUserTimeZone->time_zone->timezone_value;
            $time = Carbon::parse($value, 'UTC')->setTimezone($objUserSelectedTimeZone);
            $value = $time->format('Y-m-d H:i:s');
            
        }
        if($value != null)
        {
            return Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(Config::get('panel.date_format') . ' ' . Config::get('
                panel.time_format'));
        }
        return $value;
    }

    public function getDbScheduleTimeAttribute()
    {
        if(isset($this->attributes['schedule_time']))
        {
            return $this->attributes['schedule_time'];
        }
        return null;
    }

    public function getScheduleTimeAttribute()
    {
            $value = $this->attributes['schedule_time'];
            $objUserTimeZone = UserStore::where('user_id',Config::get('client_id'))->latest()->first();
            if(!empty($objUserTimeZone) && $value != null)
            {
                $objUserSelectedTimeZone = $objUserTimeZone->time_zone->timezone_value;
                $time = Carbon::parse($value, 'UTC')->setTimezone($objUserSelectedTimeZone);
                $value = $time->format('Y-m-d H:i:s');
                
            }
            if($value != null)
            {
                return Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(Config::get('panel.date_format') . ' ' .Config::get('panel.time_format'));
            }
            return $value;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
    * The collections that belong to the product.
    */
    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'product_collection','product_id','collection_id')->using(new class extends Pivot {
                use HasUUID;
            });
    }

    public function medias()
    {
        return $this->hasMany(ProductMedium::class)->orderBy('reorder');
    }

    /**
    * The product that belong to the collection.
    */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags', 'product_id', 'tag_id');
    }

    public function invetory_stock()
    {
        return $this->hasMany(InventoryStock::class)->whereNull('product_variant_option_id');
    }

    public function getStockStatusAttribute()
    {
        if(isset($this->attributes['price'])){
            $price = $this->attributes['price'];
            if($price <= 0 || $price == null){
                return false;
            }
        }

        if(!isset($this->attributes['is_continue_selling'])){
            return true;
        }

        $pid = $this->attributes['id'];
        $continueSelling = $this->attributes['is_continue_selling'];
        if($continueSelling == 1){
            return true;
        } else {
            $stock = InventoryStock::where('product_id', $pid)->whereNull('product_variant_option_id')->where('available_quantity', '>', 0)->first();

            if(!empty($stock)){
                return true;
            }
        }

        return false;
    }

    public function getQuantityAttribute()
    {
        $pid = $this->attributes['id'];
        $quantity = InventoryStock::select(DB::raw('sum(available_quantity)  as quantity'))
        ->where('product_id', $pid)
        ->whereNull('product_variant_option_id')
        ->where('available_quantity', '>', 0)
        ->first();

        if($quantity){
            $qty = $quantity->quantity;
            return (int)$qty;
        }

        return 0;
    }

    public function getOriginalPriceAttribute()
    {
        if(isset($this->attributes['price'])){
            $today_date = Carbon::now();
            $intOriginalPrice = $this->attributes['price'];
            if(isset($this->attributes['special_product_status']) && $this->attributes['special_product_status']  && $this->attributes['special_price'] > 0)
            {
                $expire_date = Carbon::createFromFormat('Y-m-d', $this->attributes['expiry_date']);
                $data_difference = $today_date->diffInDays($expire_date, false);  

                if($data_difference > -1) {
                    $intOriginalPrice = $this->attributes['special_price'];
                }
            }

            return $intOriginalPrice;
        }
        return 0;
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

    public function getIsWishlistAttribute(){
        if(Auth::check()){
            $found = Wishlist::where('product_id', $this->attributes['id'])->where('user_id', Auth::user()->id)->first(); 
            if($found){
                return true;
            }
            return false;
        } 
        return false;
    }

    public function order_products()
    {
        return $this->hasMany(OrderProduct::class);
    }
     public function discount_products()
    {
        return $this->hasMany(DiscountProduct::class,'product_id','id');
    }
}
