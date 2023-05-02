<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\MainMenu;
class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MainMenu::truncate();
        $data = [
            ['id' => 1, 'menuname' => 'Home' , 'setlink' => 'directurl', 'url' => '/', 'order' => 0, 'level' => 1 ],
            ['id' => 2, 'menuname' => 'Shop' , 'setlink' => 'directurl', 'url' => '/products/all', 'order' => 1, 'level' => 1 ],
            ['id' => 3, 'menuname' => 'Collections' , 'setlink' => 'directurl', 'url' => '/collections', 'order' => 2, 'level' => 1 ]
        ];
        MainMenu::insert($data);
    }
}
