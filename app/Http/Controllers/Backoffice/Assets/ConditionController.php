<?php

namespace App\Http\Controllers\Backoffice\Assets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Backoffice\Assets\ConditionService;

class ConditionController extends Controller
{
    protected $condition;

    public function __construct(ConditionService $conditionService){
        $this->condition = $conditionService;
    }

    public function index(){
        return $this->condition->index();
    }

    public function createCondition(Request $request){
        return $this->condition->create($request);
    }

    public function detailCondition(Request $request){
        return $this->condition->detail($request);
    }

    public function updateCondition(Request $request){
        return $this->condition->update($request);
    }

    public function deleteCondition(Request $request){
        return $this->condition->delete($request);
    }
}
