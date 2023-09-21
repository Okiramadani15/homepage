<?php

namespace App\Http\Controllers\Backoffice\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Backoffice\Setting\PurchaseLocationService;

class PurchaseLocationController extends Controller
{
    protected $purchaseLocation;

    public function __construct(PurchaseLocationService $purchaseLocationService){
        $this->purchaseLocation = $purchaseLocationService;
    }

    public function index(Request $request){
        return $this->purchaseLocation->index($request);
    }

    public function pagination(Request $request){
        return $this->purchaseLocation->pagination($request);
    }

    public function create(Request $request){
        return $this->purchaseLocation->create($request);
    }

    public function detail(Request $request){
        return $this->purchaseLocation->detail($request);
    }

    public function update(Request $request){
        return $this->purchaseLocation->update($request);
    }

    public function delete(Request $request){
        return $this->purchaseLocation->delete($request);
    }
}
