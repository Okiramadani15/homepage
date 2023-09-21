<?php

namespace App\Services\Backoffice\Setting;

use App\Models\Backoffice\Setting\Gender;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GenderService{
    public function index(){
        try{
            $gender = Gender::orderBy('name', 'asc')->get();

            if($gender){
                return response()->json([
                    'status' => 'success',
                    'data' => $gender,
                    'message' => 'Success'
                ]);
            }else{
                return response()->json([
                    'status' => 'fail',
                    'data' => [],
                    'message' => 'Failed'
                ]);
            }
        }catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }
}