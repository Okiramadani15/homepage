<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Backoffice\DashboardService;

class DashboardController extends Controller
{
    protected $dashboard;

    public function __construct(DashboardService $dashboardService){
        $this->dashboard = $dashboardService;
    }

    public function index(Request $request){
        return $this->dashboard->index();
    }

    public function chart(Request $request){
        return $this->dashboard->chart();
    }
}
