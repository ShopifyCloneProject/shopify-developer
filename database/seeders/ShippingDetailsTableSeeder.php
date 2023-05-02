<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingDetail;

class ShippingDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShippingDetail::truncate();
        $shipping_details = [
            ['id' => 1,'name' => 'ShipRocket','email' => null, 'password' => null,'access_token' => null,'secret_key' => null,'test_mode' => 0],
            ['id' => 2,'name' => 'Ithinklogistics','email' => null, 'password' => null,'access_token' => null,'secret_key' => null,'test_mode' => 0]
        ];

        ShippingDetail::insert($shipping_details);
    }
}
