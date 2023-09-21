<?php

namespace App\Models\Backoffice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class EducationalCalendar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'educational_calendar';

    protected $fillable = [
        'id',
        'description',
        'from',
        'to',
        'id_semester',
    ];

    protected $appends = ['date', 'title', 'start', 'end', 'allDay'];

    public function semester(){
        return $this->belongsTo(Setting\Semester::class, 'id_semester', 'id');
    }

    public function getDateAttribute(){
        $dayFrom = Carbon::parse($this->from)->format('d');
        $monthFrom = Carbon::parse($this->from)->format('m');
        $yearFrom = Carbon::parse($this->from)->format('Y');

        $dayTo = Carbon::parse($this->to)->format('d');
        $monthTo = Carbon::parse($this->to)->format('m');
        $yearTo = Carbon::parse($this->to)->format('Y');

        $monthFromName = "";
        if($monthFrom == '01'){
            $monthFromName = "Januari";
        }elseif($monthFrom == '02'){
            $monthFromName = "Februari";
        }elseif($monthFrom == '03'){
            $monthFromName = "Maret";
        }elseif($monthFrom == '04'){
            $monthFromName = "April";
        }elseif($monthFrom == '05'){
            $monthFromName = "Mei";
        }elseif($monthFrom == '06'){
            $monthFromName = "Juni";
        }elseif($monthFrom == '07'){
            $monthFromName = "Juli";
        }elseif($monthFrom == '08'){
            $monthFromName = "Agustus";
        }elseif($monthFrom == '09'){
            $monthFromName = "September";
        }elseif($monthFrom == '10'){
            $monthFromName = "Oktober";
        }elseif($monthFrom == '11'){
            $monthFromName = "November";
        }elseif($monthFrom == '12'){
            $monthFromName = "Desember";
        }else{
            $monthFromName = "Null";
        }

        $monthToName = "";
        if($monthTo == '01'){
            $monthToName = "Januari";
        }elseif($monthTo == '02'){
            $monthToName = "Februari";
        }elseif($monthTo == '03'){
            $monthToName = "Maret";
        }elseif($monthTo == '04'){
            $monthToName = "April";
        }elseif($monthTo == '05'){
            $monthToName = "Mei";
        }elseif($monthTo == '06'){
            $monthToName = "Juni";
        }elseif($monthTo == '07'){
            $monthToName = "Juli";
        }elseif($monthTo == '08'){
            $monthToName = "Agustus";
        }elseif($monthTo == '09'){
            $monthToName = "September";
        }elseif($monthTo == '10'){
            $monthToName = "Oktober";
        }elseif($monthTo == '11'){
            $monthToName = "November";
        }elseif($monthTo == '12'){
            $monthToName = "Desember";
        }else{
            $monthToName = "Null";
        }

        if($this->from != $this->to && $yearFrom != $yearTo){
            return $dayFrom . ' ' . $monthFromName . ' ' . $yearFrom . ' s.d. ' . $dayTo . ' ' . $monthToName . ' ' . $yearTo;
        }elseif($this->from != $this->to && $yearFrom == $yearTo){
            return $dayFrom . ' ' . $monthFromName . ' s.d. ' . $dayTo . ' ' . $monthToName . ' ' . $yearTo;
        }else{
            return $dayFrom . ' ' . $monthFromName . ' ' . $yearFrom;
        }
    }

    public function getTitleAttribute(){
        return $test = $this->description;
    }

    public function getStartAttribute(){
        return $this->from . ' 00:00:01';
    }

    public function getEndAttribute(){
        return $this->to . ' 00:00:01';
    }

    public function getAllDayAttribute(){
        return true;
    }
}
