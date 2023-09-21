<?php

namespace App\Models\Backoffice\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class TermDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'term_detail';

    protected $fillable = [
        'id',
        'id_term',
        'key',
        'name',
    ];
}
