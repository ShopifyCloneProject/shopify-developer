<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentMethod::truncate();
        $methods = [
            ['id' => 1, 'title' => 'Razorpay' , 'status' => 1 ],
            ['id' => 2, 'title' => 'PayTm' , 'status' => 1 ],
            ['id' => 3, 'title' => 'Payzed' , 'status' => 1 ],
            ['id' => 4, 'title' => 'Cashfree', 'status' => 1 ],
            ['id' => 5, 'title' => 'Instamojo' , 'status' => 1 ],
            ['id' => 6, 'title' => 'COD' , 'status' => 1 ],
        ];

        PaymentMethod::insert($methods);
    }
}
