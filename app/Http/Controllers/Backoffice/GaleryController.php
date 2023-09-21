<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Backoffice\GaleryService;

class GaleryController extends Controller
{
    protected $galery;

    public function __construct(GaleryService $galeryService){
        $this->galery = $galeryService;
    }

    public function index(Request $request){
        return $this->galery->index($request);
    }

    public function updateGalery(Request $request){
        return $this->galery->updateGalery($request);
    }
}
