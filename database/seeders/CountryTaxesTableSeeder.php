<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\CountryTax;
use Config;

class CountryTaxesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CountryTax::truncate();
        $objCountries = Country::get();
        $countrytaxes = [];
        foreach($objCountries as $objCountry){

            if($objCountry->id == Config::get('DEFAULT_COUNTRY')){
                $countrytaxes[] =
                ['country_id' => $objCountry->id,'default' => 1, 'tax_percentage' => 9, 'round_value' => 0];
            }
            else{
               $countrytaxes[] = ['country_id' => $objCountry->id,'default' => 0, 'tax_percentage' => 9, 'round_value' => 0];
            }
        }
            CountryTax::insert($countrytaxes); 
    }
}
