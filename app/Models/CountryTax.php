<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CountryTax extends Model
{
    use HasFactory,SoftDeletes;

    public $table = 'country_taxes';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'country_id',
        'default',
        'tax_percentage',
        'include_tax',
        'exclude_tax',
        'charge_on_shipping',
        'charge_vat_digital_goods',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function country(){
        return $this->hasOne(Country::class, 'id');
    }

    public function state_taxes()
    {
        return $this->hasMany(StateTax::class,'country_taxes_id','country_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
