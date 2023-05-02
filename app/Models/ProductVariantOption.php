<?php

namespace App\Models;

use \DateTimeInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Auth;
use DB;

class ProductVariantOption extends Model
{
    use SoftDeletes,HasFactory;

    public const IS_PRODUCT_CHARGE_RADIO = [
        '1' => 'Yes',
        '0' => 'No',
    ];

    public $table = 'product_variant_options';

    protected $dates = [
        'expiry_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'product_id',
        'variant_option_1_id',
        'variant_option_2_id',
        'variant_option_3_id',
        'src',
        'src_alt_text',
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
        'is_special_product',
        'special_price',
        'start_schedule_date',
        'expiry_date',
        'special_product_status',
        'is_shipping',
        'is_taxable',
        'reorder',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'variant_name',
        'is_wishlist',
        'stock_status',
        'quantity'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant_option_1()
    {
        return $this->belongsTo(VariantOption::class, 'variant_option_1_id');
    }

    public function variant_option_2()
    {
        return $this->belongsTo(VariantOption::class, 'variant_option_2_id');
    }

    public function variant_option_3()
    {
        return $this->belongsTo(VariantOption::class, 'variant_option_3_id');
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

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function getExpiryDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setExpiryDateAttribute($value)
    {
        $this->attributes['expiry_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getVariantNameAttribute()
    {
       $name = VariantOption::find($this->attributes['variant_option_1_id'])->options;

       if($this->attributes['variant_option_2_id'] > 0)
       {
            $name .= ' / ';
            $name .= VariantOption::find($this->attributes['variant_option_2_id'])->options;
       }

       if($this->attributes['variant_option_3_id'] > 0)
       {
            $name .= ' / ';
            $name .= VariantOption::find($this->attributes['variant_option_3_id'])->options;
       }

       return $name;
    }

    public function variant_media()
    {
       return $this->hasMany(VariantMedium::class, 'product_variant_id','id');
    }

    public function invetory_stock()
    {
        return $this->hasMany(InventoryStock::class);
    }

    public function getStockStatusAttribute()
    {
        $price = $this->attributes['price'];
        if($price <= 0 || $price == null){
            return false;
        }

        if(!isset($this->attributes['is_continue_selling'])){
            return true;
        }

        $id = $this->attributes['id'];
        $pid = $this->attributes['product_id'];
        $continueSelling = $this->attributes['is_continue_selling'];
        if($continueSelling == 1){
            return true;
        } else {
            $stock = InventoryStock::where('product_id', $pid)->where('product_variant_option_id', $id)->where('available_quantity', '>', 0)->first();

            if($stock){
                return true;
            }
        }

        return false;
    }

    public function getQuantityAttribute()
    {
        $id = $this->attributes['id'];
        $pid = $this->attributes['product_id'];
        $quantity = InventoryStock::select(DB::raw('sum(available_quantity)  as quantity'))
        ->where('product_id', $pid)
        ->where('product_variant_option_id', $id)
        ->where('available_quantity', '>', 0)
        ->first();

        if($quantity){
            $qty = $quantity->quantity;
            return (int)$qty;
        }

        return 0;
    }

    public function getIsWishlistAttribute(){
        if(Auth::check()){
            $found = Wishlist::where('product_id', $this->attributes['product_id'])->where('user_id', Auth::user()->id)->first(); 
            if($found){
                return true;
            }

            return false;
        } 

        return false;
    }
}
