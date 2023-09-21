<?php

namespace App\Models\Backoffice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Galery extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'galery';

    protected $fillable = [
        'id',
        'path',
        'preview',
    ];
}
