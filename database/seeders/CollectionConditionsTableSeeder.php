<?php

namespace Database\Seeders;

use App\Models\Condition;
use Illuminate\Database\Seeder;

class CollectionConditionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Condition::truncate();
        $conditions = [
			['id' => 1, 'title' => 'is equal to' , 'status' => 1 ],
			['id' => 2, 'title' => 'is not equal to' , 'status' => 1 ],
			['id' => 3, 'title' => 'is greater than' , 'status' => 1 ],
			['id' => 4, 'title' => 'is less than' , 'status' => 1 ],
			['id' => 5, 'title' => 'starts with' , 'status' => 1 ],
			['id' => 6, 'title' => 'ends with' , 'status' => 1 ],
			['id' => 7, 'title' => 'contains' , 'status' => 1 ],
			['id' => 8, 'title' => 'does not contain' , 'status' => 1 ],
			['id' => 9, 'title' => 'is not empty' , 'status' => 1 ],
			['id' => 10, 'title' => 'is empty' , 'status' => 1 ],
		];

		Condition::insert($conditions);
    }
}
