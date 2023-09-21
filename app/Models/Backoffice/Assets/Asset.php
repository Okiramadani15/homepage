<?php

namespace App\Models\Backoffice\Assets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'assets';

    protected $appends = ['label', 'value', 'location', 'asset_code'];

    protected $fillable = [
        'id',
        'name',
        'merk',
        'serial_number',
        'size',
        'material',
        'date_of_purchase',
        'code',
        'group_code',
        'id_purchase_location',
        'total',
        'price',
        'description',
        'barcode',
        'id_location',
        'id_work_unit',
        'type',
        'photo',
        'condition_good',
        'condition_not_good',
        'condition_very_bad',
        'quantity_loan'
    ];

    public function getLabelAttribute(){
        $locationName = ItemLocation::where('id', $this->id_location)->select('name')->first();
        $name = $locationName ? $locationName->name : "";
        $total = $this->total - $this->quantity_loan;
        return $this->name . ' - ' . $name . '( ' . $total . ' )';
    }

    public function getValueAttribute(){
        return $this->id;
    }

    public function getLocationAttribute(){
        $locationName = ItemLocation::where('id', $this->id_location)->select('name')->first();
        return $locationName->name ?? "";
    }

    public function location(){
        return $this->belongsTo(ItemLocation::class, 'id_location', 'id');
    }

    public function work_unit(){
        return $this->belongsTo(\App\Models\Backoffice\Setting\WorkUnit::class, 'id_work_unit', 'id');
    }

    public function type_asset(){
        return $this->belongsTo(\App\Models\Backoffice\Setting\TermDetail::class, 'type', 'key')->where('id_term', 3);
    }

    public function group_of_code(){
        return $this->belongsTo(\App\Models\Backoffice\Setting\GroupCode::class, 'group_code', 'id');
    }

    public function purchase_location(){
        return $this->belongsTo(\App\Models\Backoffice\Setting\PurchaseLocation::class, 'id_purchase_location', 'id');
    }

    public function getAssetCodeAttribute(){
        $locationCode = ItemLocation::where('id', $this->id_location)->select('code')->first();
        $unitCode = \App\Models\Backoffice\Setting\WorkUnit::where('id', $this->id_work_unit)->select('code')->first();
        $groupCode = \App\Models\Backoffice\Setting\GroupCode::where('id', $this->group_code)->select('code')->first();
        $purchaseLocation = \App\Models\Backoffice\Setting\PurchaseLocation::where('id', $this->id_purchase_location)->first();

        $location = $locationCode->code ?? "0";
        $unit = $unitCode->code ?? "0";
        $group = $groupCode->code ?? "0";
        $code = $this->code ?? "0";
        $purchase = $purchaseLocation->code ?? "0";

        return $location . "." . $unit . '.' . $purchase . '.' . $this->date_of_purchase . '.' . $group . '.' . $code;
    }
}
