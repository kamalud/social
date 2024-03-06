<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'admin',
            'role'=>"admin",
            'email'=>'admin@gmail.com',
            'password'=>bcrypt('admin@gmail.com'),
        ]);
        User::create([
            'name'=>'kamal',
            'role'=>'user',
            'email'=>'kamal@gmail.com',
            'password'=>bcrypt('kamal@gmail.com'),
        ]);
        User::create([
            'name'=>'jamal',
            'role'=>'user',
            'email'=>'jamal@gmail.com',
            'password'=>bcrypt('jamal@gmail.com'),
        ]);

        User::create([
            'name'=>'josim',
            'role'=>'user',
            'email'=>'josim@gmail.com',
            'password'=>bcrypt('josim@gmail.com'),
        ]);

        User::create([
            'name'=>'joyel',
            'role'=>'user',
            'email'=>'joyel@gmail.com',
            'password'=>bcrypt('joyel@gmail.com'),
        ]);
    }
}
