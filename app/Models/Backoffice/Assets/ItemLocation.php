<?php

namespace App\Models\Backoffice\Assets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemLocation extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'item_location';

    protected $appends = ['label', 'value'];

    protected $fillable = [
        'id',
        'name',
        'code',
    ];

    public function getLabelAttribute(){
        return $this->name;
    }

    public function getValueAttribute(){
        return $this->id;
    }

}
