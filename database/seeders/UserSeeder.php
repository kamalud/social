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
            'name'=>'kamal',
            'email'=>'kamal@gmail.com',
            'password'=>bcrypt('kamal@gmail.com'),
        ]);
        User::create([
            'name'=>'jamal',
            'email'=>'jamal@gmail.com',
            'password'=>bcrypt('jamal@gmail.com'),
        ]);

        User::create([
            'name'=>'josim',
            'email'=>'josim@gmail.com',
            'password'=>bcrypt('josim@gmail.com'),
        ]);

        User::create([
            'name'=>'joyel',
            'email'=>'joyel@gmail.com',
            'password'=>bcrypt('joyel@gmail.com'),
        ]);
    }
}
