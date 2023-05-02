<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $user = User::get();
        if($user->IsEmpty())
        {
            $users = [
                [
                    'id'             => 1,
                    'name'           => 'Admin',
                    'email'          => 'admin@admin.com',
                    'password'       => bcrypt('shopify'),
                    'remember_token' => null,
                    'last_name'      => '',
                    'mobile'         => '',
                    'google'         => '',
                    'facebook'       => '',
                    'company'        => '',
                    'tags'           => '',
                    'role_id'        => 1
                ],
            ];
            User::insert($users);
        }
    }
}
