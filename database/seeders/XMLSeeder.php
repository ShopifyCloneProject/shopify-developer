<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\XMLFeed;

class XMLSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        XMLFeed::truncate();
        $data = [
            ['id' => 1, 'choose1' => 10 , 'choose2' => 74, 'default' => 1, 'createtime'=> 1 ],           
            ['id' => 2, 'choose1' => 11 , 'choose2' => 78, 'default' => 1, 'createtime'=> 1 ],           
            ['id' => 3, 'choose1' => 12 , 'choose2' => 80, 'default' => 1, 'createtime'=> 1 ],           
            ['id' => 4, 'choose1' => 13 , 'choose2' => 110, 'default' => 1, 'createtime'=> 1 ],           
            ['id' => 5, 'choose1' => 14 , 'choose2' => 111, 'default' => 1, 'createtime'=> 1 ],           
            ['id' => 6, 'choose1' => 26 , 'choose2' => 106, 'default' => 1, 'createtime'=> 1 ],           
            ['id' => 7, 'choose1' => 29 , 'choose2' => 84, 'default' => 1, 'createtime'=> 1 ],           
            ['id' => 8, 'choose1' => 30 , 'choose2' => 83, 'default' => 1, 'createtime'=> 1 ],           
            ['id' => 9, 'choose1' => 36 , 'choose2' => 112, 'default' => 1, 'createtime'=> 1 ],           
            ['id' => 10, 'choose1' => 41 , 'choose2' => 113, 'default' => 1, 'createtime'=> 1 ],           
            ['id' => 11, 'choose1' => 54 , 'choose2' => 99, 'default' => 1, 'createtime'=> 1 ],           
            ['id' => 12, 'choose1' => 42 , 'choose2' => 114, 'default' => 1, 'createtime'=> 1 ],           
            ['id' => 13, 'choose1' => 37 , 'choose2' => 108, 'default' => 1, 'createtime'=> 1 ],           
        ];
        XMLFeed::insert($data);
    }
}
