<?php

namespace App\Services\Backoffice;

use App\Models\Backoffice\Test;

class TestService {
    public function getTest(){
        $test = Test::all();

        if($test){
            return ['status' => 'success', 'data' => $test, 'message' => 'Success'];
        }else{
            return ['status' => 'fail', 'message' => 'Failed'];
        }
    }

    public function detail($data){
        try{
            $detail = Test::find($data->id);

            if($detail){
                return response()->json([
                    'status' => 'success',
                    'data' => $detail,
                    'message' => 'Success'
                ]);
            }else{
                return response()->json([
                    'status' => 'fail',
                    'data' => [],
                    'message' => "Data doesn't exist"
                ]);
            }
        }catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create($data){
        try{
            $create = new Test;
            $create->name = $data->name;
            $create->age = $data->age;
            $create->save();

            if($create){
                return ['status' => 'success', 'message' => 'Success'];
            }else{
                return ['status' => 'fail', 'message' => 'Failed'];
            }

        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function update($data){
        try{
            $update = Test::where('id', $data->id)->first();
            $update->name = $data->name;
            $update->age = $data->age;
            $update->save();

            if($update){
                return ['status' => 'success', 'message' => 'Success'];
            }else{
                return ['status' => 'fail', 'message' => 'Failed'];
            }

        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function delete($data){
        try{
            $delete = Test::where('id', $data->id)->delete();

            if($delete){
                return ['status' => 'success', 'message' => 'Success'];
            }else{
                return ['status' => 'fail', 'message' => 'Failed'];
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }


}
