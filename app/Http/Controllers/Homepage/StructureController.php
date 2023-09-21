<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StructureController extends Controller
{
    public function index(){
        return view('foundation-structure.index');
    }
}
