<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;
use App\Models\StateTax;
use Config;

class StateTaxesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StateTax::truncate();
        $objStates = State::get();
        $statetaxes = [];
        foreach($objStates as $objState){
            $statetaxes[] = ['country_taxes_id' => $objState->country_id ,'state_id' => $objState->id,'state_tax_percentage' => 18, 'text' => 'IGST', 'tax_additional' => 1];
        }
            StateTax::insert($statetaxes); 
    }
}
