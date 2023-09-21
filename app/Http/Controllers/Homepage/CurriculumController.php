<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CurriculumController extends Controller
{
    public function ips(){
        return view('curriculum-ips.index');
    }

    public function ipa(){
        return view('curriculum-ipa.index');
    }

    public function tsnawiyah(){
        return view('curriculum-tsanawiyah.index');
    }
}
