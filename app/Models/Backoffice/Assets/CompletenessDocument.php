<?php

namespace App\Models\Backoffice\Assets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompletenessDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'completeness_document';

    protected $fillable = [
        'id',
        'id_completeness',
        'id_detail',
        'photo',
        'document',
    ];

    public function detail(){
        return $this->belongsTo(DetailProcurement::class, 'id_detail', 'id');
    }
}
