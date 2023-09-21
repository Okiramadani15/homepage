<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Backoffice\Setting\Position;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = ['Super Admin', 'Admin Sarpras', 'Bendahara'];

        foreach($data as $value){
            $position = new Position;
            $position->name = $value;
            $position->save();
        }
    }
}
