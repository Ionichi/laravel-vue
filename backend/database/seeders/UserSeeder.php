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
        // DB::table('users')->insert([
        //     'username' => 'admin',
        //     'fullname' => 'Admin Management',
        //     'gender' => 'L',
        //     'email' => 'admin@gmail.com',
        //     'password' => Hash::make('12345678'),
        //     'role' => 'A',
        //     'status' => 'A'
        // ]);

        $data = [
            [
                'username' => 'admin',
                'fullname' => 'Admin Management',
                'gender' => 'L',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'A',
                'status' => 'A'
            ],
        ];

        foreach($data as $data) {
            User::create($data);
        }
    }
}
