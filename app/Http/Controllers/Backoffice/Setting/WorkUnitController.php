<?php

namespace App\Http\Controllers\Backoffice\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Backoffice\Setting\WorkUnitService;

class WorkUnitController extends Controller
{
    protected $workUnit;

    public function __construct(WorkUnitService $workUnitService){
        $this->workUnit = $workUnitService;
    }

    public function index(Request $request){
        return $this->workUnit->index($request);
    }

    public function pagination(Request $request){
        return $this->workUnit->pagination($request);
    }

    public function create(Request $request){
        return $this->workUnit->create($request);
    }

    public function detail(Request $request){
        return $this->workUnit->detail($request);
    }

    public function update(Request $request){
        return $this->workUnit->update($request);
    }

    public function delete(Request $request){
        return $this->workUnit->delete($request);
    }
}
