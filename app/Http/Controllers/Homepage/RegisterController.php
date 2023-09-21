<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backoffice\Banner;

class RegisterController extends Controller
{
    public function index(){
        $banner = Banner::first();

        return view('register.index', compact('banner'));
    }
}
