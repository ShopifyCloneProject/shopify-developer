<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StateTax extends Model
{
    use HasFactory,SoftDeletes;

    public $table = 'state_taxes';

    public const TAX_ADDITIONAL = [
        '0' => 'added to 12% federal tax',
        '1' => 'instead of 12% federal tax',
        '2' => 'compounded on top of 12% federal tax',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'country_taxes_id',
        'state_id',
        'state_tax_percentage',
        'text',
        'tax_additional',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function country_tax()
    {
        return $this->belongsTo(CountryTax::class,'country_taxes_id','country_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
