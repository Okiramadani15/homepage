<?php

namespace App\Http\Controllers\Backoffice\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Backoffice\Setting\TermService;

class TermController extends Controller
{
    protected $term;

    public function __construct(TermService $termService){
        $this->term = $termService;
    }

    public function index(Request $request){
        return $this->term->index($request);
    }

    public function detail(Request $request){
        return $this->term->detail($request);
    }
}
