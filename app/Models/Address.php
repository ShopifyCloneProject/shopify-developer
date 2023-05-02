<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Country;

class Address extends Model
{
    use SoftDeletes,HasFactory;

    public const STATUS_RADIO = [
        '1' => 'Shipping',
        '0' => 'Billing',
    ];

    public $table = 'addresses';

    protected $appends = [
        'Countryname',
        'Statename',
        'Shortcode',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'store_status',
        'user_id',
        'mac_id',
        'first_name',
        'last_name',
        'location_name',
        'company_name',
        'address',
        'address_2',
        'phone_code',
        'email',
        'mobile',
        'status',
        'is_default',
        'postal_code',
        'country_id',
        'state_id',
        'city_name',
        'is_save',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function getCountrynameAttribute(){
        $strCountryName = '';
        if(isset($this->attributes['state_id'])){
            $objCountry = Country::where('id',$this->attributes['country_id'])->first();
            if(!empty($objCountry)){
                $strCountryName = $objCountry->name;
            }
    }
        return $strCountryName;
    }

    public function getShortcodeAttribute(){
        $strCountryShortCode = '';
        if(isset($this->attributes['state_id'])){
            $objCountry = Country::where('id',$this->attributes['country_id'])->first();
            if(!empty($objCountry)){
                $strCountryShortCode = $objCountry->short_code;
            }
    }
        return $strCountryShortCode;
    }

    public function getStatenameAttribute(){
        $strStateName = '';
        if(isset($this->attributes['state_id'])){
            $objState = State::where('id',$this->attributes['state_id'])->first();
            if(!empty($objState)){
                $strStateName = $objState->name;
            }
        }
        return $strStateName; 
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


}
