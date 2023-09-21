<?php

namespace App\Services\Backoffice\Setting;

use App\Models\Backoffice\Setting\Semester;

class SemesterService{
    public function index(){
        try{
            $semester = Semester::orderBy('id', 'asc')->get();

            if($semester){
                return response()->json([
                    'status' => 'success',
                    'data' => $semester,
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