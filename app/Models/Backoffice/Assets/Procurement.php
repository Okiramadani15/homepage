<?php

namespace App\Models\Backoffice\Assets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Procurement extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'procurement';

    protected $fillable = [
        'id',
        'id_user_responsible',
        'id_user_approved',
        'type',
        'id_status',
        'reason',
        'reason_reject',
    ];

    protected $appends = ['total_procurement', 'total_received', 'is_complete'];

    public function responsible(){
        return $this->belongsTo(\App\Models\User::class, 'id_user_responsible', 'id');
    }

    public function approval(){
        return $this->belongsTo(\App\Models\User::class, 'id_user_approved', 'id');
    }

    public function status_procurement(){
        return $this->belongsTo(\App\Models\Backoffice\Setting\TermDetail::class, 'id_status', 'key')->where('id_term', 1);
    }

    public function detail(){
        return $this->hasMany(DetailProcurement::class, 'id_procurement', 'id');
    }

    public function type_procurement(){
        return $this->belongsTo(\App\Models\Backoffice\Setting\TermDetail::class, 'type', 'key')->where('id_term', 3);
    }

    public function getTotalProcurementAttribute(){
        $total = DetailProcurement::where('id_procurement', $this->id)->sum('quantity');
        return $total;
    }

    public function getTotalReceivedAttribute(){
        $total = DetailProcurement::where('id_procurement', $this->id)->sum('quantity_received');
        return $total;
    }

    public function getIsCompleteAttribute(){
        $isComplete = DetailProcurement::where('id_status', 1)->count();
        if($isComplete > 0){
            return false;
        }else{
            return true;
        }
    }
}
