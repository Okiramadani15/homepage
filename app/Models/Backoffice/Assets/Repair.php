<?php

namespace App\Models\Backoffice\Assets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Repair extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'asset_repair';

    protected $fillable = [
        'id',
        'id_user_responsible',
        'id_user_approved',
        'id_status',
        'reason',
        'type',
        'reason_reject',
    ];

    public function responsible(){
        return $this->belongsTo(\App\Models\User::class, 'id_user_responsible', 'id');
    }

    public function approval(){
        return $this->belongsTo(\App\Models\User::class, 'id_user_approval', 'id');
    }

    public function status_repair(){
        return $this->belongsTo(\App\Models\Backoffice\Setting\TermDetail::class, 'id_status', 'key')->where('id_term', 1);
    }

    public function detail(){
        return $this->hasMany(DetailRepair::class, 'id_repair', 'id');
    }

    public function image_report(){
        return $this->hasOne(RepairImageReport::class, 'id_repair', 'id');
    }
}
