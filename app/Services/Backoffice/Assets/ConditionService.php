<?php

namespace App\Services\Backoffice\Assets;

use App\Models\Backoffice\Assets\ItemCondition;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ConditionService{
    public function index(){
        try{
            $condition = ItemCondition::orderBy('name', 'asc')->get();

            if($condition){
                return response()->json([
                    'status' => 'success',
                    'data' => $condition,
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

    public function create($data){
        try{
            $rules = [
                'name' => 'required|unique:item_condition',
            ];
    
            $messages = [
                'name.required' => 'Tidak Boleh Kosong',
                'name.unique' => 'Kondisi Barang sudah terdaftar'
            ];
    
            $validator = Validator::make($data->all(), $rules, $messages);
            if($validator->fails()){
                return response()->json([
                    'status' => 'fail',
                    'date' => $validator->errors(),
                    'message' => 'Invalid data request',
                ]);
            }

            $condition = ItemCondition::create([
                'name' => $data->name
            ]);
            
            if($condition){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success'
                ]);
            }else{
                return response()->json([
                    'status' => 'fail',
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
            $condition = ItemCondition::find($data->id);

            if($condition){
                return response()->json([
                    'status' => 'success',
                    'data' => $condition,
                    'message' => 'Berhasil mengubah data'
                ]);
            }else{
                return response()->json([
                    'status' => 'success',
                    'data' => [],
                    'message' => 'Belum ada data'
                ]);
            }
        }catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update($data){
        try{
            $rules = [
                'id' => 'required',
                'name' => [
                    'required',
                    Rule::unique('item_condition')->ignore($data->id, 'id')
                ],
            ];
    
            $messages = [
                'id.required' => 'id tidak boleh kosong',
                'name.required' => 'Tidak boleh kosong',
                'name.unique' => 'Data sudah terdaftar',
            ];
    
            $validator = Validator::make($data->all(), $rules, $messages);
            if($validator->fails()){
                return response()->json([
                    'status' => 'fail',
                    'data' => $validator->errors(),
                    'message' => 'invalid data request',
                ]);
            }

            $update = ItemCondition::where('id', $data->id)
            ->update([
                'name' => $data->name
            ]);

            if($update){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Berhasil mengubah data'
                ]);
            }else{
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Data tidak ditemukan'
                ]);
            }

        }catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete($data){
        try{
            $delete = ItemCondition::where('id', $data->id)->delete();
            if($delete){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Berhasil Menghapus data'
                ]);
            }else{
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Data tidak ditemukan'
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