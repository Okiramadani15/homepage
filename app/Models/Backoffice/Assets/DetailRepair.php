<?php

namespace App\Models\Backoffice\Assets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailRepair extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'detail_repair';

    protected $fillable = [
        'id',
        'id_repair',
        'id_asset',
        'quantity',
        'price',
        'type',
        'id_status'
    ];

    public function asset(){
        return $this->belongsTo(Asset::class, 'id_asset', 'id');
    }
}
