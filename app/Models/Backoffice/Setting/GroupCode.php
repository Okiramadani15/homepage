<?php

namespace App\Models\Backoffice\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class GroupCode extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'group_code';

    protected $fillable = [
        'id',
        'name',
        'code'
    ];
}
