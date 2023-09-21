<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Backoffice\Setting\Semester;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $semester = ['Semester Ganjil', 'Semester Genap'];

        for($i = 0; $i < count($semester); $i++){
            Semester::create([
                'name' => $semester[$i]
            ]);
        }
    }
}
