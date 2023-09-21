<?php

namespace App\Http\Controllers\Backoffice\Assets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Backoffice\Assets\AssetProcurementService;
use Illuminate\Support\Facades\Validator;

class AssetProcurementController extends Controller
{
    protected $procurement;

    public function __construct(AssetProcurementService $procurementService){
        $this->procurement = $procurementService;
    }

    public function index(Request $request){
        return $this->procurement->index($request);
    }

    public function create(Request $request){
        return $this->procurement->create($request);
    }

    public function delete(Request $request){
        return $this->procurement->delete($request);
    }

    public function detail(Request $request){
        return $this->procurement->detail($request);
    }

    public function update(Request $request){
        return $this->procurement->update($request);
    }

    // public function declineProcurement(Request $request){
    //     return $this->procurement->declineProcurement($request);
    // }

    public function updateDetailProcurement(Request $request){
        return $this->procurement->updateDetailProcurement($request);
    }

    public function onProcess(Request $request){
        return $this->procurement->onProcess($request);
    }

    public function onApprove(Request $request){
        return $this->procurement->onApprove($request);
    }

    public function onDecline(Request $request){
        return $this->procurement->onDecline($request);
    }

    public function onCompleted(Request $request){
        return $this->procurement->onCompleted($request);
    }

    public function changeStatus(Request $request){
        return $this->procurement->changeStatus($request);
    }

    public function exportProcurement(Request $request){
        return $this->procurement->exportProcurement($request);
    }

    public function finishDetailProcurement(Request $request){
        $rules = [
            'id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'document' => 'required|max:2048',
        ];

        $messages = [
            'id.required' => 'Tidak boleh kosong',
            'image.required' => 'Tidak boleh kosong',
            'image.image' => 'Bukti photo harus gambar',
            'image.mimes' => 'Format foto harus jpeg,png,jpg',
            'image.max' => 'Maksimal 2MB',
            'document.required' => 'Tidak boleh kosong',
            'document.max' => 'Maksimal 2MB',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return response()->json([
                'status' => 'fail',
                'error' => $validator->errors(),
                'message' => $validator->errors()->first()
            ], 403);
        }else{
            return $this->procurement->finishDetailProcurement($request);
        }
    }

    public function documentProcurement(Request $request){
        return $this->procurement->documentProcurement($request);
    }
}
