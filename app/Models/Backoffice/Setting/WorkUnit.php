<?php

namespace App\Models\Backoffice\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'work_unit';

    protected $fillable = [
        'id',
        'name',
        'code',
    ];
}
