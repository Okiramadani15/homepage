<?php

namespace App\Http\Controllers\Backoffice\Assets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Backoffice\Assets\AssetBleachingService;

class AssetBleachingController extends Controller
{
    protected $bleaching;

    public function __construct(AssetBleachingService $assetBleachingService){
        $this->bleaching = $assetBleachingService;
    }

    public function index(Request $request){
        return $this->bleaching->index($request);
    }

    public function create(Request $request){
        return $this->bleaching->create($request);
    }

    public function changeStatus(Request $request){
        return $this->bleaching->changeStatus($request);
    }
}
