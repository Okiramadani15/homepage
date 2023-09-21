<?php

namespace App\Models\Backoffice\Assets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailLoan extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'detail_loan';

    protected $fillable = [
        'id',
        'id_loan',
        'id_asset',
        'id_location',
        'type',
        'quantity_received',
        'quantity',
    ];

    public function asset(){
        return $this->belongsTo(Asset::class, 'id_asset', 'id');
    }

    public function location_now(){
        return $this->belongsTo(ItemLocation::class, 'id_location', 'id');
    }
}
