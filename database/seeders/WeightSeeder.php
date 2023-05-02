<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Weightmanage;

class WeightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Weightmanage::truncate();
        $wights = [
            ['id' => 1,'title' => 'Kilogram' ,'status' => 1, 'short_code' => 'kg'],
            ['id' => 2,'title' => 'Gram' ,'status' => 1,'short_code' => 'gm'],            
            ['id' => 3,'title' => 'lb' ,'status' => 1,'short_code' => 'lbs'],            
            ['id' => 4,'title' => 'oz' ,'status' => 1,'short_code' => 'oz']        
        ];

        Weightmanage::insert($wights);
    }
}
