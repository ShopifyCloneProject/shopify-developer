<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Language::truncate();
        $languages = [
            ['id' => 1, 'name' => 'English', 'status' => '1'],
        ];

        Language::insert($languages);
    }
}
