<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [[
          'name'=>'Admin',
          'email'=>'admin@gmail.com',
          'password'=> bcrypt("password123"),
          'role'=>'admin'
        ],
        [
          'name'=>'Gabriel Indrawan',
          'email'=>'abelepic3@gmail.com',
          'password'=> bcrypt("abelepic3"),
          'role'=>'user'
        ]];
        //
        foreach ($users as $user){
            User::create($user);
        }
    }
}
