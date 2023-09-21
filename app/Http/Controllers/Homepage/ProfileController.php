<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(){
        return view('history.index');
    }

    public function visionMision(){
        return view('vision-and-mission.index');
    }
}
