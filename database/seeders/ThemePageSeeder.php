<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ThemeSetting;

class ThemePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       ThemeSetting::truncate();
        $data = [
            ['id' => 1, 'page' => 1 , 'sectionname' => 'header', 'status' => 1, 'order' => 0 ],
            ['id' => 2, 'page' => 1 , 'sectionname' => 'slider', 'status' => 1, 'order' => 1 ],
            ['id' => 3, 'page' => 1 , 'sectionname' => 'accessories', 'status' => 1, 'order' => 2],
            ['id' => 4, 'page' => 1 , 'sectionname' => 'logo', 'status' => 1, 'order' => 3 ],
            ['id' => 5, 'page' => 1 , 'sectionname' => 'collection', 'status' => 1, 'order' => 4 ],
            ['id' => 6, 'page' => 1 , 'sectionname' => 'besttrends', 'status' => 1, 'order' => 5 ],
            ['id' => 7, 'page' => 1 , 'sectionname' => 'newarriaval', 'status' => 1, 'order' => 6 ],
            ['id' => 8, 'page' => 1 , 'sectionname' => 'footer', 'status' => 1, 'order' => 7 ],
            ['id' => 9, 'page' => 2 , 'sectionname' => 'header', 'status' => 1, 'order' => 0 ],
            ['id' => 10, 'page' => 2 , 'sectionname' => 'detail', 'status' => 1, 'order' => 1 ],
            ['id' => 11, 'page' => 2 , 'sectionname' => 'promocode', 'status' => 1, 'order' => 2 ],
            ['id' => 12, 'page' => 2 , 'sectionname' => 'description', 'status' => 1, 'order' => 3 ],
            ['id' => 13, 'page' => 2 , 'sectionname' => 'related', 'status' => 1, 'order' => 4 ],
            ['id' => 14, 'page' => 2 , 'sectionname' => 'footer', 'status' => 1, 'order' => 5 ],
        ];
        ThemeSetting::insert($data);
    }
}
