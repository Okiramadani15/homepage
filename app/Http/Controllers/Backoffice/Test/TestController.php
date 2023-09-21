<?php

namespace App\Http\Controllers\Backoffice\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Backoffice\TestService;

class TestController extends Controller
{
    protected $test;

    public function __construct(TestService $testService){
        $this->test = $testService;
    }

    public function index(Request $request){
        return $this->test->getTest();
    }

    public function getDetail(Request $request){
        return $this->test->detail($request);
    }

    public function createTest(Request $request){
        return $this->test->create($request);
    }

    public function updateTest(Request $request){
        return $this->test->update($request);
    }

    public function deleteTest(Request $request){
        return $this->test->delete($request);
    }
}
