<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backoffice\Galery;
use App\Models\Backoffice\Banner;
use App\Models\Backoffice\Assets\Asset;

class HomepageController extends Controller
{
    public function index(Request $request){
        $galery = Galery::orderBy('id', 'asc')->get();
        $banner = Banner::first();

        return view('home.index', compact('galery', 'banner'));
    }

    public function detailAsset(Request $request){
        $asset = Asset::where('id', $request->id)->with(['group_of_code', 'location', 'work_unit', 'purchase_location'])->first();
        return view('asset.index', compact('asset'));
        return $asset;
    }
}
