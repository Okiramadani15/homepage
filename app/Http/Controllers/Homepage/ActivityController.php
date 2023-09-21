<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backoffice\Activity;

class ActivityController extends Controller
{
    public function index(){
        $mostView = Activity::with([
            'creator' => function($q){
                $q->select('id', 'name');
            }
        ])
        ->orderBy('id', 'asc')
        ->first();

        return view('activity.index', compact('mostView'));
    }

    public function detail(){
        return view('detail-activity.index');
    }
}
