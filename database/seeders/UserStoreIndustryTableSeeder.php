<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserStoreIndustry;

class UserStoreIndustryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         UserStoreIndustry::truncate();
        $industry = [
            ['id' => 1, 'user_id' => '0', 'title' => 'Beauty', 'status' => 1 ],
            ['id' => 2, 'user_id' => '0', 'title' => 'Clothing', 'status' => 1 ],
            ['id' => 3, 'user_id' => '0', 'title' => 'Electronics', 'status' => 1 ],
            ['id' => 4, 'user_id' => '0', 'title' => 'Furniture', 'status' => 1 ],
            ['id' => 5, 'user_id' => '0', 'title' => 'Handcrafts', 'status' => 1 ],
            ['id' => 6, 'user_id' => '0', 'title' => 'Jewelry', 'status' => 1 ],
            ['id' => 7, 'user_id' => '0', 'title' => 'Painting', 'status' => 1 ],
            ['id' => 8, 'user_id' => '0', 'title' => 'Photography', 'status' => 1 ],
            ['id' => 9, 'user_id' => '0', 'title' => 'Restaurants', 'status' => 1 ],
            ['id' => 10, 'user_id' => '0', 'title' => 'Groceries', 'status' => 1 ],
            ['id' => 11, 'user_id' => '0', 'title' => 'Other food &amp; drink', 'status' => 1 ],
            ['id' => 12, 'user_id' => '0', 'title' => 'Sports', 'status' => 1 ],
            ['id' => 13, 'user_id' => '0', 'title' => 'Toys', 'status' => 1 ],
            ['id' => 14, 'user_id' => '0', 'title' => 'Services', 'status' => 1 ],
            ['id' => 15, 'user_id' => '0', 'title' => 'Virtual services', 'status' => 1 ],
            ['id' => 16, 'user_id' => '0', 'title' => 'Other', 'status' => 1 ],
            ['id' => 17, 'user_id' => '0', 'title' => 'I haven’t decided yet', 'status' => 1 ],
        ];

        UserStoreIndustry::insert($industry);
    }
}
