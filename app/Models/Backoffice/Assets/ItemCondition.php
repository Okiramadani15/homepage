<?php

namespace App\Models\Backoffice\Assets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemCondition extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'item_condition';

    protected $fillable = [
        'id',
        'name'
    ];
}
