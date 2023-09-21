<?php

namespace App\Services\Backoffice\Setting;

use App\Models\Backoffice\Setting\WorkUnit;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class WorkUnitService{
    public function index(){
        try{
            $workUnit = WorkUnit::orderBy('name', 'asc')->get();

            if($workUnit){
                return response()->json([
                    'status' => 'success',
                    'data' => $workUnit,
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
            
            $workUnit = new WorkUnit;
            $workUnit = $workUnit->orderBy('name', 'asc');
            $workUnit = $workUnit->where('name', 'like', '%'.$data->search.'%');
            $workUnit = $workUnit->paginate($data->limit)->onEachSide(1)->toArray();
            
            $listPage = [];
            $i = 1;

            foreach($workUnit['links'] as $value){
                $explodeUrl = explode("page=", $value['url']);

                if($i == 1){
                    $tmpWorkUnit = [
                        'page' => $workUnit['current_page'] != 1 ? $workUnit['current_page'] - 1 : 1,
                        'label' => "Previous",
                        'active' => $value['active'],
                    ];
                }elseif($i == $workUnit['last_page'] + 2){
                    $tmpWorkUnit = [
                        'page' => $workUnit['current_page'] < $workUnit['last_page'] ? $workUnit['current_page'] + 1 : $workUnit['current_page'],
                        'label' => "Next",
                        'active' => $value['active'],
                    ];
                }elseif($value['url'] != null){
                    $tmpWorkUnit = [
                        'page' => $explodeUrl[1],
                        'label' => $value['label'],
                        'active' => $value['active'],
                    ];
                }else{
                    $tmpWorkUnit = [
                        'page' => "",
                        'label' => "...",
                        'active' => false
                    ];
                }
                array_push($listPage, $tmpWorkUnit);
                $i += 1;
            }

            if($workUnit){
                return response()->json([
                    'status' => 'success',
                    'pagination' => [
                        'current_page' => $workUnit['current_page'],
                        'last_page' => $workUnit['last_page'],
                        'previous_page' => $workUnit['current_page'] != 1 ? $workUnit['current_page'] - 1 : 1,
                        'next_page' => $workUnit['current_page'] < $workUnit['last_page'] ? $workUnit['current_page'] + 1 : $workUnit['current_page'],
                        'per_page' => $workUnit['per_page'],
                        'total' => $workUnit['total'],
                        'from' => $workUnit['from'],
                        'to' => $workUnit['to'],
                        'list_page' => $listPage,
                    ],
                    'data' => $workUnit['data'],
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
                    Rule::unique('work_unit', 'name')->withoutTrashed()
                ],
                'code' => [
                    'required',
                    Rule::unique('work_unit', 'code')->withoutTrashed()
                ]
            ];
    
            $messages = [
                'name.required' => 'Tidak Boleh Kosong',
                'name.unique' => 'Satuan Kerja sudah terdaftar',
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

            $workUnit = WorkUnit::create([
                'name' => $data->name,
                'code' => $data->code,
            ]);
            
            if($workUnit){
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
            $workUnit = WorkUnit::find($data->id);

            if($workUnit){
                return response()->json([
                    'status' => 'success',
                    'data' => $workUnit,
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
                    Rule::unique('work_unit')->ignore($data->id, 'id')->withoutTrashed()
                ],
                'code' => [
                    'required',
                    Rule::unique('work_unit')->ignore($data->id, 'id')->withoutTrashed()
                ],
            ];
    
            $messages = [
                'id.required' => 'id tidak boleh kosong',
                'name.required' => 'Tidak boleh kosong',
                'name.unique' => 'Satuan kerja sudah terdaftar',
                'code.required' => 'Tidak boleh kosong',
                'code.unique' => 'Satuan kerja sudah terdaftar',
            ];
    
            $validator = Validator::make($data->all(), $rules, $messages);
            if($validator->fails()){
                return response()->json([
                    'status' => 'fail',
                    'message' => $validator->errors()->first(),
                ]);
            }

            $update = WorkUnit::where('id', $data->id)
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
            $delete = WorkUnit::where('id', $data->id)->delete();

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