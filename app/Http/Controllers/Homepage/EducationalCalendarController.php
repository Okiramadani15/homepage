<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backoffice\EducationalCalendar;
use Carbon\Carbon;
use Calendar;


class EducationalCalendarController extends Controller
{
    public function index(){
        $ganjil = EducationalCalendar::where('id_semester', 1)->get();
        $genap = EducationalCalendar::where('id_semester', 2)->get();
        $firstYear = EducationalCalendar::orderBy('from', 'asc')->first();
        $lastYear = EducationalCalendar::orderBy('to', 'desc')->first();
        $ta = Carbon::parse($firstYear->from)->format('Y') .'/'. Carbon::parse($lastYear->to)->format('Y');

        return view('boarding-school-calendar.index', compact('ganjil', 'genap', 'ta',));
    }

    public function dataCalendar(){
        return $calendar = EducationalCalendar::orderBy('from', 'asc')->get();
    }
}
