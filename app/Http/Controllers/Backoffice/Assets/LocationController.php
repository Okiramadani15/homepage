<?php

namespace App\Http\Controllers\Backoffice\Assets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Backoffice\Assets\LocationService;

class LocationController extends Controller
{
    protected $location;

    public function __construct(LocationService $locationService){
        $this->location = $locationService;
    }

    public function index(Request $request){
        return $this->location->getAllLocation($request);
    }

    public function pagination(Request $request){
        return $this->location->pagination($request);
    }

    public function createLocation(Request $request){
        return $this->location->create($request);
    }

    public function getDetailLocation(Request $request){
        return $this->location->detail($request);
    }

    public function updateLocation(Request $request){
        return $this->location->update($request);
    }

    public function deleteLocation(Request $request){
        return $this->location->delete($request);
    }
}
