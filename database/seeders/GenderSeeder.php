<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Backoffice\Setting\Gender;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gender = ['Laki-laki', 'Perempuan'];

        for($i = 0; $i < count($gender); $i++){
            Gender::create([
                'name' => $gender[$i]
            ]);
        }
    }
}
