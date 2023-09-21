<?php

namespace App\Models\Backoffice\Assets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'asset_loan';

    protected $fillable = [
        'id',
        'id_user_responsible',
        'id_user_approval',
        'id_status',
        'type',
        'reason',
        'reason_reject',
        'from',
        'to',
    ];

    protected $appends = ['total_loan', 'total_received'];

    public function responsible(){
        return $this->belongsTo(\App\Models\User::class, 'id_user_responsible', 'id');
    }

    public function approval(){
        return $this->belongsTo(\App\Models\User::class, 'id_user_approval', 'id');
    }

    public function status_loan(){
        return $this->belongsTo(\App\Models\Backoffice\Setting\TermDetail::class, 'id_status', 'key')->where('id_term', 1);
    }

    public function detail(){
        return $this->hasMany(DetailLoan::class, 'id_loan', 'id');
    }

    public function image_report(){
        return $this->hasOne(LoanReportImage::class, 'id_loan', 'id');
    }

    public function getTotalLoanAttribute(){
        $total = DetailLoan::where('id_loan', $this->id)->sum('quantity');
        return $total;
    }

    public function getTotalReceivedAttribute(){
        $total = DetailLoan::where('id_loan', $this->id)->sum('quantity_received');
        return $total;
    }
}
