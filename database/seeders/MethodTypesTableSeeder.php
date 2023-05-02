<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MethodType;

class MethodTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         MethodType::truncate();
        $methodTypes = [
            ['id' => 1, 'payment_method_id' => '1' , 'payment_type_id' => '1' , 'is_enabled' => 1 ],
            ['id' => 2, 'payment_method_id' => '1' , 'payment_type_id' => '2' , 'is_enabled' => 1 ],
            ['id' => 3, 'payment_method_id' => '1' , 'payment_type_id' => '3' , 'is_enabled' => 1 ],
            ['id' => 4, 'payment_method_id' => '1' , 'payment_type_id' => '4' , 'is_enabled' => 1 ],
            ['id' => 5, 'payment_method_id' => '2' , 'payment_type_id' => '1' , 'is_enabled' => 1 ],
            ['id' => 6, 'payment_method_id' => '2' , 'payment_type_id' => '2' , 'is_enabled' => 1 ],
            ['id' => 7, 'payment_method_id' => '2' , 'payment_type_id' => '3' , 'is_enabled' => 1 ],
            ['id' => 8, 'payment_method_id' => '2' , 'payment_type_id' => '4' , 'is_enabled' => 1 ],
            ['id' => 9, 'payment_method_id' => '3' , 'payment_type_id' => '1' , 'is_enabled' => 1 ],
            ['id' => 10, 'payment_method_id' => '3' , 'payment_type_id' => '2' , 'is_enabled' => 1 ],
            ['id' => 11, 'payment_method_id' => '3' , 'payment_type_id' => '3' , 'is_enabled' => 1 ],
            ['id' => 12, 'payment_method_id' => '3' , 'payment_type_id' => '4' , 'is_enabled' => 1 ],
            ['id' => 13, 'payment_method_id' => '4' , 'payment_type_id' => '1' , 'is_enabled' => 1 ],
            ['id' => 14, 'payment_method_id' => '4' , 'payment_type_id' => '2' , 'is_enabled' => 1 ],
            ['id' => 15, 'payment_method_id' => '4' , 'payment_type_id' => '3' , 'is_enabled' => 1 ],
            ['id' => 16, 'payment_method_id' => '4' , 'payment_type_id' => '4' , 'is_enabled' => 1 ],
            ['id' => 17, 'payment_method_id' => '5' , 'payment_type_id' => '1' , 'is_enabled' => 1 ],
            ['id' => 18, 'payment_method_id' => '5' , 'payment_type_id' => '2' , 'is_enabled' => 1 ],
            ['id' => 19, 'payment_method_id' => '5' , 'payment_type_id' => '3' , 'is_enabled' => 1 ],
            ['id' => 20, 'payment_method_id' => '5' , 'payment_type_id' => '4' , 'is_enabled' => 1 ],
        ];

        MethodType::insert($methodTypes);
    }
}
