<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CheckoutSetting;

class CheckoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CheckoutSetting::truncate();
        $types = [
            ['id' => 1, 'title' => 'customerAccounts' , 'data' => '3', 'status' => 1 ],
            ['id' => 2, 'title' => 'toCheckOut' , 'data' => '1', 'status' => 1 ],
            ['id' => 3, 'title' => 'shoppingUpdate1' , 'data' => 'false' , 'status' => 1 ],
            ['id' => 4, 'title' => 'shoppingUpdate2' , 'data' => 'false', 'status' => 1 ],
            ['id' => 5, 'title' => 'fullNameOption' , 'data' => '1', 'status' => 1 ],
            ['id' => 6, 'title' => 'companyNameOption' , 'data' => '1', 'status' => 1 ],
            ['id' => 7, 'title' => 'address2Option' , 'data' => '2' , 'status' => 1 ],
            ['id' => 8, 'title' => 'shippingAddressOption' , 'data' => '1', 'status' => 1 ],
            ['id' => 9, 'title' => 'tippingOption' , 'data' => 'false', 'status' => 1 ],
            ['id' => 10, 'title' => 'orderProcessing1' , 'data' => 'false', 'status' => 1 ],
            ['id' => 11, 'title' => 'orderProcessing2' , 'data' => 'false', 'status' => 1 ],
            ['id' => 12, 'title' => 'afterOrderPaid' , 'data' => '2', 'status' => 1 ],
            ['id' => 13, 'title' => 'orderFullfilled' , 'data' => 'true', 'status' => 1 ],
            ['id' => 14, 'title' => 'additionalScript' , 'data' => '', 'status' => 1 ],
            ['id' => 15, 'title' => 'emailOption1' , 'data' => 'true', 'status' => 1 ],
            ['id' => 16, 'title' => 'emailOption2' , 'data' => 'false', 'status' => 1 ],
            ['id' => 17, 'title' => 'abandonCheckout' , 'data' => 'true', 'status' => 1 ],
            ['id' => 18, 'title' => 'sendToOption' , 'data' => '2', 'status' => 1 ],
            ['id' => 19, 'title' => 'sendAfterOption' , 'data' => '3', 'status' => 1 ],
        ];
        CheckoutSetting::insert($types);
    }
}
