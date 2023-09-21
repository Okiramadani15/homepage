<?php

namespace App\Models\Backoffice\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class PurchaseLocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'purchase_location';

    protected $fillable = [
        'id',
        'name',
        'code'
    ];
}
