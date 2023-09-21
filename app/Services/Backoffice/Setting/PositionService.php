<?php

namespace App\Services\Backoffice\Setting;

use App\Models\Backoffice\Setting\Position;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PositionService{
    public function index($data){
        try{
            $position = Position::orderBy('name', 'asc')->get();

            if($position){
                return response()->json([
                    'status' => 'success',
                    'data' => $position,
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

    public function pagination($data){
        try{
            $limit = 10;
            if($data->limit){
                $limit = $data->limit;
            }
            
            $position = new Position;
            $position = $position->orderBy('id', 'asc');
            $position = $position->where('name', 'like', '%'.$data->search.'%');
            $position = $position->paginate($data->limit)->onEachSide(1)->toArray();
            
            $listPage = [];
            $i = 1;

            foreach($position['links'] as $value){
                $explodeUrl = explode("page=", $value['url']);

                if($i == 1){
                    $tmpPosition = [
                        'page' => $position['current_page'] != 1 ? $position['current_page'] - 1 : 1,
                        'label' => "Previous",
                        'active' => $value['active'],
                    ];
                }elseif($i == $position['last_page'] + 2){
                    $tmpPosition = [
                        'page' => $position['current_page'] < $position['last_page'] ? $position['current_page'] + 1 : $position['current_page'],
                        'label' => "Next",
                        'active' => $value['active'],
                    ];
                }elseif($value['url'] != null){
                    $tmpPosition = [
                        'page' => $explodeUrl[1],
                        'label' => $value['label'],
                        'active' => $value['active'],
                    ];
                }else{
                    $tmpPosition = [
                        'page' => "",
                        'label' => "...",
                        'active' => false
                    ];
                }
                array_push($listPage, $tmpPosition);
                $i += 1;
            }

            if($position){
                return response()->json([
                    'status' => 'success',
                    'pagination' => [
                        'current_page' => $position['current_page'],
                        'last_page' => $position['last_page'],
                        'previous_page' => $position['current_page'] != 1 ? $position['current_page'] - 1 : 1,
                        'next_page' => $position['current_page'] < $position['last_page'] ? $position['current_page'] + 1 : $position['current_page'],
                        'per_page' => $position['per_page'],
                        'total' => $position['total'],
                        'from' => $position['from'],
                        'to' => $position['to'],
                        'list_page' => $listPage,
                    ],
                    'data' => $position['data'],
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

    public function createPosition($data){
        try{
            $rules = [
                'name' => [
                    'required',
                    Rule::unique('position', 'name')->withoutTrashed()
                ]
            ];
    
            $messages = [
                'name.required' => 'Tidak Boleh Kosong',
                'name.unique' => 'Jabatan sudah terdaftar'
            ];
    
            $validator = Validator::make($data->all(), $rules, $messages);
            if($validator->fails()){
                return response()->json([
                    'status' => 'fail',
                    'message' => $validator->errors()->first(),
                ]);
            }

            $position = Position::create([
                'name' => $data->name
            ]);
            
            if($position){
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

    public function detailPosition($data){
        try{
            $position = Position::find($data->id);

            if($position){
                return response()->json([
                    'status' => 'success',
                    'data' => $position,
                    'message' => 'Berhasil mengubah data'
                ]);
            }else{
                return response()->json([
                    'status' => 'fail',
                    'data' => [],
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

    public function updatePosition($data){
        try{
            $rules = [
                'id' => 'required',
                'name' => [
                    'required',
                    Rule::unique('position')->ignore($data->id, 'id')->withoutTrashed()
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
                    'message' => $validator->errors()->first(),
                ]);
            }

            $update = Position::where('id', $data->id)
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

    public function deletePosition($data){
        try{
            $delete = Position::where('id', $data->id)->delete();

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