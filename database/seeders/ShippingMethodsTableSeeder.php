<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingMethod;

class ShippingMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       ShippingMethod::truncate();
       $shipping_methods = [
            ['id' => 1, 'title' => 'ShipRocket', 'status' => 1],
            ['id' => 2, 'title' => 'Ithinklogistics', 'status' => 1],
       ]; 
       ShippingMethod::insert($shipping_methods);
    }
}
