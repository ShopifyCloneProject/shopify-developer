<?php

namespace Database\Seeders;

use App\Models\Variant;
use Illuminate\Database\Seeder;

class VariantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Variant::truncate();
        $types = [
			['id' => 1, 'title' => 'Size' , 'status' => 1 ],
			['id' => 2, 'title' => 'Color' , 'status' => 1 ],
			['id' => 3, 'title' => 'Material' , 'status' => 1 ],
			['id' => 4, 'title' => 'Style' , 'status' => 1 ],
			['id' => 5, 'title' => 'Title' , 'status' => 1 ],
		];

		Variant::insert($types);
    }
}
