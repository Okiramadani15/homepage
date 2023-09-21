<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Backoffice\Setting\Term;
use App\Models\Backoffice\Setting\TermDetail;

class TermDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $termDetailStatus = ['Pending', 'Diproses', 'Disetujui', 'Ditolak', 'Selesai'];
        $termDetailStatusProcurement = ['Belum Selesai', 'Sedang Diproses', 'Selesai', 'Ditolak'];
        $typeAsset = ['Sarana', 'Prasarana'];

        $i = 1;
        foreach($termDetailStatus as $value){
            TermDetail::create([
                'name' => $value,
                'key' => $i,
                'id_term' => 1
            ]);
            $i += 1;
        }

        $i = 1;
        foreach($termDetailStatusProcurement as $value){
            TermDetail::create([
                'name' => $value,
                'key' => $i,
                'id_term' => 2
            ]);
            $i += 1;
        }

        $i = 1;
        foreach($typeAsset as $value){
            TermDetail::create([
                'name' => $value,
                'key' => $i,
                'id_term' => 3
            ]);
            $i += 1;
        }
    }
}
