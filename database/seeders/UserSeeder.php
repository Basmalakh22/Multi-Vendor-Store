<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // using model
        User::created([
            'name' =>'basmala admin',
            'email' =>'basmala@gmail.com',
            'password' => Hash::make('password',),
            'phone_number' =>'865432',
        ]);

        //using quiry bulider
        DB::table('users')->insert([
            'name' =>'basmala',
            'email' =>'basmala2@gmail.com',
            'password' => Hash::make('password',),
            'phone_number' =>'8654322',
        ]);
    }
}
