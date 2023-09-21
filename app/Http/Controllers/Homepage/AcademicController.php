<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AcademicController extends Controller
{
    public function mts(){
        return view('mts.index');
    }

    public function ma(){
        return view('ma.index');
    }

    public function reportOnline(){
        return view('online-report.index');
    }
}
