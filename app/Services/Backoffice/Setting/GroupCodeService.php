<?php

namespace App\Services\Backoffice\Setting;

use App\Models\Backoffice\Setting\GroupCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GroupCodeService{
    public function index($data){
        try{
            $groupCode = GroupCode::orderBy('name', 'asc')->get();

            if($groupCode){
                return response()->json([
                    'status' => 'success',
                    'data' => $groupCode,
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
            
            $groupCode = new GroupCode;
            $groupCode = $groupCode->orderBy('id', 'asc');
            $groupCode = $groupCode->where('name', 'like', '%'.$data->search.'%');
            $groupCode = $groupCode->paginate($data->limit)->onEachSide(1)->toArray();
            
            $listPage = [];
            $i = 1;

            foreach($groupCode['links'] as $value){
                $explodeUrl = explode("page=", $value['url']);

                if($i == 1){
                    $tmpGroupCode = [
                        'page' => $groupCode['current_page'] != 1 ? $groupCode['current_page'] - 1 : 1,
                        'label' => "Previous",
                        'active' => $value['active'],
                    ];
                }elseif($i == $groupCode['last_page'] + 2){
                    $tmpGroupCode = [
                        'page' => $groupCode['current_page'] < $groupCode['last_page'] ? $groupCode['current_page'] + 1 : $groupCode['current_page'],
                        'label' => "Next",
                        'active' => $value['active'],
                    ];
                }elseif($value['url'] != null){
                    $tmpGroupCode = [
                        'page' => $explodeUrl[1],
                        'label' => $value['label'],
                        'active' => $value['active'],
                    ];
                }else{
                    $tmpGroupCode = [
                        'page' => "",
                        'label' => "...",
                        'active' => false
                    ];
                }
                array_push($listPage, $tmpGroupCode);
                $i += 1;
            }

            if($groupCode){
                return response()->json([
                    'status' => 'success',
                    'pagination' => [
                        'current_page' => $groupCode['current_page'],
                        'last_page' => $groupCode['last_page'],
                        'previous_page' => $groupCode['current_page'] != 1 ? $groupCode['current_page'] - 1 : 1,
                        'next_page' => $groupCode['current_page'] < $groupCode['last_page'] ? $groupCode['current_page'] + 1 : $groupCode['current_page'],
                        'per_page' => $groupCode['per_page'],
                        'total' => $groupCode['total'],
                        'from' => $groupCode['from'],
                        'to' => $groupCode['to'],
                        'list_page' => $listPage,
                    ],
                    'data' => $groupCode['data'],
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
                'name' => [
                    'required',
                    Rule::unique('group_code', 'name')->withoutTrashed()
                ],
                'code' => [
                    'required',
                    Rule::unique('group_code', 'code')->withoutTrashed()
                ],
            ];
    
            $messages = [
                'name.required' => 'Nama Golongan Tidak Boleh Kosong',
                'name.unique' => 'Golongan sudah terdaftar',
                'code.required' => 'Tidak Boleh Kosong',
                'code.unique' => 'Kode sudah terdaftar'
            ];
    
            $validator = Validator::make($data->all(), $rules, $messages);
            if($validator->fails()){
                return response()->json([
                    'status' => 'fail',
                    'message' => $validator->errors()->first(),
                ]);
            }

            $groupCode = GroupCode::create([
                'name' => $data->name,
                'code' => $data->code,
            ]);
            
            if($groupCode){
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
            $groupCode = GroupCode::find($data->id);

            if($groupCode){
                return response()->json([
                    'status' => 'success',
                    'data' => $groupCode,
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

    public function update($data){
        try{
            $rules = [
                'id' => 'required',
                'name' => [
                    'required',
                    Rule::unique('group_code')->ignore($data->id, 'id')->withoutTrashed()
                ],
                'code' => [
                    'required',
                    Rule::unique('group_code')->ignore($data->id, 'id')->withoutTrashed()
                ],
            ];
    
            $messages = [
                'id.required' => 'id tidak boleh kosong',
                'name.required' => 'Nama Golongan Tidak boleh kosong',
                'name.unique' => 'Nama Golongan sudah terdaftar',
                'code.required' => 'Kode Tidak boleh kosong',
                'code.unique' => 'Kode sudah terdaftar',
            ];
    
            $validator = Validator::make($data->all(), $rules, $messages);
            if($validator->fails()){
                return response()->json([
                    'status' => 'fail',
                    'message' => $validator->errors()->first(),
                ]);
            }

            $update = GroupCode::where('id', $data->id)
            ->update([
                'name' => $data->name,
                'code' => $data->code,
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
            $delete = GroupCode::where('id', $data->id)->delete();

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