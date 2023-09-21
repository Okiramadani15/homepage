<?php

namespace App\Http\Controllers\Backoffice\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Backoffice\Setting\GroupCodeService;

class GroupCodeController extends Controller
{
    protected $groupCode;

    public function __construct(GroupCodeService $groupCodeService){
        $this->groupCode = $groupCodeService;
    }

    public function index(Request $request){
        return $this->groupCode->index($request);
    }

    public function pagination(Request $request){
        return $this->groupCode->pagination($request);
    }

    public function create(Request $request){
        return $this->groupCode->create($request);
    }

    public function detail(Request $request){
        return $this->groupCode->detail($request);
    }

    public function update(Request $request){
        return $this->groupCode->update($request);
    }

    public function delete(Request $request){
        return $this->groupCode->delete($request);
    }
}
