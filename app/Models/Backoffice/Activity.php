<?php

namespace App\Models\Backoffice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'activity';

    protected $appends = ['created_at_id', 'limit_description'];

    protected $fillable = [
        'id',
        'title',
        'id_creator',
        'image',
        'description',
        'view',
    ];

    public function getCreatedAtIdAttribute(){
        return Carbon::parse($this->created_at)->format('d/m/Y');
    }

    public function getLimitDescriptionAttribute(){
        $limit = 5;
        $text = $this->description;
        if (str_word_count($text, 0) > $limit) {
            $words = str_word_count($text, 2);
            $pos   = array_keys($words);
            $text  = substr($text, 0, $pos[$limit]) . '...';
        }

        return $text;
    }

    public function creator(){
        return $this->belongsTo(\App\Models\User::class, 'id_creator', 'id');
    }
}
