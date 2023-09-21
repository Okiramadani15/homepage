<?php

namespace App\Http\Controllers\Backoffice\Assets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Backoffice\Assets\AssetLoanService;
use Illuminate\Support\Facades\Validator;

class AssetLoanController extends Controller
{
    protected $loan;

    public function __construct(AssetLoanService $loanService){
        $this->loan = $loanService;
    }

    public function index(Request $request){
        return $this->loan->index($request);
    }

    public function create(Request $request){
        return $this->loan->create($request);
    }

    public function delete(Request $request){
        return $this->loan->delete($request);
    }

    public function detail(Request $request){
        return $this->loan->detail($request);
    }

    public function changeStatus(Request $request){
        if($request->id_status == 2 || $request->id_status == 5){
            $rules = [
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ];
    
            $messages = [
                'photo.required' => 'Bukti foto tidak boleh kosong',
                'photo.image' => 'Bukti photo harus gambar',
                'photo.mimes' => 'Format foto harus jpeg,png,jpg',
                'photo.max' => 'Maksimal 2MB',
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if($validator->fails()){
                return response()->json([
                    'status' => 'fail',
                    'error' => $validator->errors(),
                    'message' => $validator->errors()->first()
                ], 403);
            }else{
                return $this->loan->changeStatus($request);
            }
        }
    }

    public function update(Request $request){
        return $this->loan->update($request);
    }

    public function updateDetailLoan(Request $request){
        return $this->loan->updateDetailLoan($request);
    }

    public function onDecline(Request $request){
        return $this->loan->onDecline($request);
    }
}
