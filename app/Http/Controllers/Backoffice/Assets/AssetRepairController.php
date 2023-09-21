<?php

namespace App\Http\Controllers\Backoffice\Assets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Backoffice\Assets\AssetRepairService;
use Illuminate\Support\Facades\Validator;

class AssetRepairController extends Controller
{
    protected $repair;

    public function __construct(AssetRepairService $repairService){
        $this->repair = $repairService;
    }

    public function index(Request $request){
        return $this->repair->index($request);
    }

    public function create(Request $request){
        return $this->repair->create($request);
    }

    public function detail(Request $request){
        return $this->repair->detail($request);
    }

    public function update(Request $request){
        return $this->repair->update($request);
    }

    public function changeStatus(Request $request){
        if($request->id_status == 5){
            $rules = [
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ];
    
            $messages = [
                'photo.required' => 'Tidak boleh kosong',
                'image.image' => 'Bukti photo harus gambar',
                'image.mimes' => 'Format foto harus jpeg,png,jpg',
                'image.max' => 'Maksimal 2MB',
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if($validator->fails()){
                return response()->json([
                    'status' => 'fail',
                    'error' => $validator->errors(),
                    'message' => $validator->errors()->first()
                ], 403);
            }else{
                return $this->repair->changeStatus($request);
            }
        }else{
            return $this->repair->changeStatus($request);
        }
    }

    public function delete(Request $request){
        return $this->repair->delete($request);
    }
}
