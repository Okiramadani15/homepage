<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Backoffice\BannerService;

class BannerController extends Controller
{
    protected $banner;

    public function __construct(BannerService $bannerService){
        $this->banner = $bannerService;
    }

    public function index(Request $request){
        return $this->banner->index($request);
    }

    public function updateBanner(Request $request){
        return $this->banner->updateBanner($request);
    }
}
