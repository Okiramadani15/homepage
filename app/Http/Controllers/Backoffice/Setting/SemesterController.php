<?php

namespace App\Http\Controllers\Backoffice\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Backoffice\Setting\SemesterService;

class SemesterController extends Controller
{
    protected $semester;

    public function __construct(SemesterService $semesterService){
        $this->semester = $semesterService;
    }

    public function index(Request $request){
        return $this->semester->index($request);
    }
}
