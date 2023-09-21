<?php

namespace App\Models\Backoffice\Assets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepairImageReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'repair_report_image';

    protected $fillable = [
        'id',
        'id_repair',
        'image_before',
        'image_after',
        'bill',
    ];
}
