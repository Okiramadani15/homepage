<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Homepage\ActivityService;
use App\Http\Requests\ActivityRequest;
use App\Http\Requests\ActivityUpdateRequest;

class ActivityController extends Controller
{
    protected $activity;

    public function __construct(ActivityService $activityService){
        $this->activity = $activityService;
    }

    public function index(Request $request){
        return $this->activity->index($request);
    }

    public function create(ActivityRequest $request){
        return $this->activity->create($request);
    }

    public function detail(Request $request){
        return $this->activity->detail($request);
    }

    public function update(ActivityUpdateRequest $request){
        return $this->activity->update($request);
    }

    public function delete(Request $request){
        return $this->activity->delete($request);
    }
}
