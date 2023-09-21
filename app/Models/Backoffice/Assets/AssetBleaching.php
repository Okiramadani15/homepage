<?php

namespace App\Models\Backoffice\Assets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetBleaching extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'asset_bleaching';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_asset',
        'id_responsible',
        'id_approve',
        'status',
        'created_at',
        'updated_at',
    ];

    public function asset(){
        return $this->belongsTo(Asset::class, 'id_asset', 'id');
    }

    public function responsible(){
        return $this->belongsTo(\App\Models\User::class, 'id_responsible', 'id');
    }

    public function approve(){
        return $this->belongsTo(\App\Models\User::class, 'id_approve', 'id');
    }
}
