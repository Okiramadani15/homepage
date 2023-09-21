<?php

namespace App\Models\Backoffice\Assets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailProcurement extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'detail_procurement';

    protected $fillable = [
        'id',
        'id_procurement',
        'name',
        'quantity',
        'quantity_received',
        'price',
        'id_status',
        'year',
        'type',
        'location',
        'unit',
        'document_rab',
        'id_asset',
    ];

    public function status(){
        return $this->belongsTo(\App\Models\Backoffice\Setting\TermDetail::class, 'id_status', 'key')->where('id_term', 2);
    }

    public function type(){
        return $this->belongsTo(\App\Models\Backoffice\Setting\TermDetail::class, 'type', 'key')->where('id_term', 3);
    }

    public function location(){
        return $this->belongsTo(ItemLocation::class, 'location', 'id');
    }

    public function unit(){
        return $this->belongsTo(\App\Models\Backoffice\Setting\WorkUnit::class, 'unit', 'id');
    }

    public function document(){
        return $this->belongsTo(CompletenessDocument::class, 'id', 'id_detail');
    }
}
