<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentType;

class PaymentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentType::truncate();
        $types = [
            ['id' => 1, 'name' => 'Visa' , 'status' => 1 ],
            ['id' => 2, 'name' => 'Mastercard' , 'status' => 1 ],
            ['id' => 3, 'name' => 'American Express' , 'status' => 1 ],
            ['id' => 4, 'name' => 'RuPay',  'status' => 1 ],
            ['id' => 5, 'name' => 'Airtel Money' , 'status' => 1 ],
            ['id' => 6, 'name' => 'Freecharge' , 'status' => 1 ],
            ['id' => 7, 'name' => 'Ola Money' , 'status' => 1 ],
            ['id' => 8, 'name' => 'Paytm' , 'status' => 1 ],
            ['id' => 9, 'name' => 'MobiKwik' , 'status' => 1 ],
            ['id' => 10, 'name' => 'PayZapp' , 'status' => 1 ],
            ['id' => 11, 'name' => 'Diners Club' , 'status' => 1 ],
            ['id' => 12, 'name' => 'Maestro' , 'status' => 1 ],
            ['id' => 13, 'name' => 'Amazon Pay' , 'status' => 1 ],
            ['id' => 14, 'name' => 'PayPal' , 'status' => 1 ],
        ];

        PaymentType::insert($types);
    }
}
