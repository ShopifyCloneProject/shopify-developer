<?php

namespace Database\Seeders;

use App\Models\ConditionTitle;
use Illuminate\Database\Seeder;

class ConditionTitlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	ConditionTitle::truncate();
        $title = [
			['id' => 1, 'title' => 'Product title' , 'status' => 1 ,'collection_condition' => '1,2,5,6,7,8'],
			['id' => 2, 'title' => 'Product type' , 'status' => 1 ,'collection_condition' => '1,2,5,6,7,8'],
			['id' => 3, 'title' => 'Product vendor' , 'status' => 1 ,'collection_condition' => '1,2,5,6,7,8'],
			['id' => 4, 'title' => 'Product price' , 'status' => 1 ,'collection_condition' => '1,2,3,4'],
			['id' => 5, 'title' => 'Product tag' , 'status' => 1 ,'collection_condition' => '1'],
			['id' => 6, 'title' => 'Compare at price' , 'status' => 1 ,'collection_condition' => '1,2,3,4,9,10'],
			['id' => 7, 'title' => 'Weight' , 'status' => 1 ,'collection_condition' => '1,2,3,4'],
			['id' => 8, 'title' => 'Inventory stock' , 'status' => 1 ,'collection_condition' => '1,3,4'],
			['id' => 9, 'title' => 'Variant’s title' , 'status' => 1 ,'collection_condition' => '1,2,5,6,7,8'],
		];

		ConditionTitle::insert($title);
    }
}
