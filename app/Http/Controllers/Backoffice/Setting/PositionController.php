<?php

namespace App\Http\Controllers\Backoffice\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Backoffice\Setting\PositionService;

class PositionController extends Controller
{
    protected $position;

    public function __construct(PositionService $positionService){
        $this->position = $positionService;
    }

    public function index(Request $request){
        return $this->position->index($request);
    }

    public function pagination(Request $request){
        return $this->position->pagination($request);
    }

    public function create(Request $request){
        return $this->position->createPosition($request);
    }

    public function detail(Request $request){
        return $this->position->detailPosition($request);
    }

    public function update(Request $request){
        return $this->position->updatePosition($request);
    }

    public function delete(Request $request){
        return $this->position->deletePosition($request);
    }
}
