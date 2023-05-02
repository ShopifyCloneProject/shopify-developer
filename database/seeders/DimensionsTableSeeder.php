<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dimension;

class DimensionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Dimension::truncate();
        $dimensions = [
            ['id' => 1,'title' => 'Meter' ,'status' => 1, 'short_code' => 'm'],
            ['id' => 2,'title' => 'Centimeter' ,'status' => 1,'short_code' => 'cm'],            
            ['id' => 3,'title' => 'Millimeter' ,'status' => 1,'short_code' => 'mm'],            
            ['id' => 4,'title' => 'Inch' ,'status' => 1,'short_code' => 'in'],            
            ['id' => 5,'title' => 'Yards' ,'status' => 1,'short_code' => 'yd'],            
        ];

        Dimension::insert($dimensions); 
    }
}
