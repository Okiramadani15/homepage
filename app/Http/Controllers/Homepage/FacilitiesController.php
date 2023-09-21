<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FacilitiesController extends Controller
{
    public function index(){
        return view('facilities.index');
    }
}
