<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('rahasia'),
            'address' => 'Medan',
            'phone' => '0812-1111-2222',
            'id_gender' => 1,
            'id_position' => 1,
            'photo' => '/assets/illustrations/male.jpg',
            'level' => 0,
        ]);

        // User::create([
        //     'name' => 'Admin',
        //     'email' => 'admin@gmail.com',
        //     'password' => bcrypt('rahasia'),
        //     'address' => 'Medan',
        //     'phone' => '0812 1111 2222',
        //     'id_gender' => 1,
        //     'id_position' => 1,
        //     'photo' => '/assets/illustrations/male.jpg',
        //     'level' => 1,
        // ]);

        // User::create([
        //     'name' => 'Bendahara',
        //     'email' => 'bendahara@gmail.com',
        //     'password' => bcrypt('rahasia'),
        //     'address' => 'Medan',
        //     'phone' => '0812 1111 2222',
        //     'id_gender' => 1,
        //     'id_position' => 2,
        //     'photo' => '/assets/illustrations/male.jpg',
        //     'level' => 1,
        // ]);
    }
}
