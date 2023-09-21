<?php

namespace App\Models\Backoffice\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Term extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'term';

    protected $fillable = [
        'id',
        'name',
    ];
}
