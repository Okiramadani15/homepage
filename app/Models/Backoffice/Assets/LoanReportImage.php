<?php

namespace App\Models\Backoffice\Assets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanReportImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'loan_report_image';

    protected $fillable = [
        'id',
        'id_loan',
        'image_before',
        'image_after',
    ];
}
