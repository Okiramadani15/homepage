<?php

namespace App\Services\Backoffice\Setting;

use App\Models\Backoffice\Setting\Term;
use App\Models\Backoffice\Setting\TermDetail;

class TermService{
    public function index($data){
        try{
            $term = Term::all();

            if($term){
                return response()->json([
                    'status' => 'success',
                    'data' => $term,
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

    public function detail($data){
        try{
            $term = TermDetail::where('id_term', $data->id_term)->orderBy('key', 'asc')->get();

            if($term){
                return response()->json([
                    'status' => 'success',
                    'data' => $term,
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