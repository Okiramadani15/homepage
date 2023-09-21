<?php

namespace App\Models\Backoffice\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Semester extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'semester';

    protected $fillable = [
        'id',
        'name'
    ];
}
