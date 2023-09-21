<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Backoffice\Setting\Term;

class TermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $term = ['Status Pengadaan', 'Status Barang', 'Jenis Asset'];

        foreach($term as $value){
            Term::create([
                'name' => $value
            ]);
        }
    }
}
