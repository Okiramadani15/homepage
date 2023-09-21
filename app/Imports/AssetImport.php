<?php

namespace App\Imports;

use App\Models\Backoffice\Assets\Asset;
use App\Models\Backoffice\Setting\PurchaseLocation;
use App\Models\Backoffice\Assets\ItemLocation;
use App\Models\Backoffice\Setting\WorkUnit;
use App\Models\Backoffice\Setting\GroupCode;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\DB;

class AssetImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $purchaseLocation = PurchaseLocation::where(DB::raw('lower(name)'), '=', strtolower($row[7]))->first();
        if($purchaseLocation){
            $id_purchase_location = $purchaseLocation->id;
        }else{
            $purchaseLocation = PurchaseLocation::first();
            $id_purchase_location = $purchaseLocation->id;
        }

        $itemLocation = ItemLocation::where(DB::raw('lower(name)'), '=', strtolower($row[10]))->first();
        if($itemLocation){
            $id_location = $itemLocation->id;
        }else{
            $itemLocation = ItemLocation::first();
            $id_location = $itemLocation->id;
        }

        $workUnit = WorkUnit::where(DB::raw('lower(name)'), '=', strtolower($row[11]))->first();
        if($workUnit){
            $id_work_unit = $workUnit->id;
        }else{
            $workUnit = WorkUnit::first();
            $id_work_unit = $workUnit->id;
        }

        $groupOfCode = GroupCode::where(DB::raw('lower(name)'), 'LIKE', strtolower($row[13]))->first();
        if($groupOfCode){
            $groupCode = $groupOfCode->id;
        }else{
            $groupOfCode = GroupCode::first();
            $groupCode = $groupOfCode->id;
        }

        if(strtolower($row[12]) == strtolower('Sarana')){
            $type = 1;
        }elseif(strtolower($row[12]) == strtolower('Prasarana')){
            $type = 2;
        }else{
            $type = 1;
        }
        
        return new Asset([
            'name' => $row[1],
            'merk' => $row[2],
            'serial_number' => $row[3],
            'size' => $row[4],
            'material' => $row[5],
            'date_of_purchase' => $row[6],
            'id_purchase_location' => $id_purchase_location,
            'total' => $row[8],
            'price' => $row[9],
            'id_location' => $id_location,
            'id_work_unit' => $id_work_unit,
            'type' => $type,
            'group_code' => $groupCode,
            'code' => $row[14],
            'condition_good' => $row[15],
            'condition_not_good' => $row[16],
            'condition_very_bad' => $row[17],
        ]);
    }
}
