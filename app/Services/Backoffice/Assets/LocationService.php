<?php

namespace App\Services\Backoffice\Assets;

use App\Models\Backoffice\Assets\ItemLocation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LocationService {
    public function getAllLocation($data){
        try{
            $location = ItemLocation::orderBy('name', 'asc')->get();
            
            if($location){
                return response()->json([
                    'status' => 'success',
                    'data' => $location,
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
            $location = new ItemLocation;
            $location = $location->orderBy('name', 'asc');
            $location = $location->where('name', 'like', '%'.$data->search.'%');
            $location = $location->paginate($data->limit)->onEachSide(1)->toArray();
            
            $listPage = [];
            $i = 1;
            foreach($location['links'] as $value){
                $explodeUrl = explode("page=", $value['url']);

                if($i == 1){
                    $tmpLocation = [
                        'page' => $location['current_page'] != 1 ? $location['current_page'] - 1 : 1,
                        'label' => "Previous",
                        'active' => $value['active'],
                    ];
                }elseif($i == $location['last_page'] + 2){
                    $tmpLocation = [
                        'page' => $location['current_page'] < $location['last_page'] ? $location['current_page'] + 1 : $location['current_page'],
                        'label' => "Next",
                        'active' => $value['active'],
                    ];
                }elseif($value['url'] != null){
                    $tmpLocation = [
                        'page' => $explodeUrl[1],
                        'label' => $value['label'],
                        'active' => $value['active'],
                    ];
                }else{
                    $tmpLocation = [
                        'page' => "",
                        'label' => "...",
                        'active' => false
                    ];
                }
                array_push($listPage, $tmpLocation);
                $i += 1;
            }
            
            if($location){
                return response()->json([
                    'status' => 'success',
                    'pagination' => [
                        'current_page' => $location['current_page'],
                        'last_page' => $location['last_page'],
                        'previous_page' => $location['current_page'] != 1 ? $location['current_page'] - 1 : 1,
                        'next_page' => $location['current_page'] < $location['last_page'] ? $location['current_page'] + 1 : $location['current_page'],
                        'per_page' => $location['per_page'],
                        'total' => $location['total'],
                        'from' => $location['from'],
                        'to' => $location['to'],
                        'list_page' => $listPage,
                    ],
                    'data' => $location['data'],
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
                    Rule::unique('item_location', 'name')->withoutTrashed()
                ],
                'code' => [
                    'required',
                    Rule::unique('item_location', 'code')->withoutTrashed()
                ]
            ];
    
            $messages = [
                'name.required' => 'Tidak Boleh Kosong',
                'name.unique' => 'Lokasi sudah terdaftar',
                'code.required' => 'Tidak Boleh Kosong',
                'code.unique' => 'Kode sudah terdaftar'
            ];
    
            $validator = Validator::make($data->all(), $rules, $messages);
            if($validator->fails()){
                return response()->json([
                    'status' => 'fail',
                    'data' => $validator->errors(),
                    'message' => $validator->errors()->first(),
                ]);
            }

            $location = ItemLocation::create([
                'name' => $data->name,
                'code' => $data->code,
            ]);
            
            if($location){
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
            $location = ItemLocation::find($data->id);

            if($location){
                return response()->json([
                    'status' => 'success',
                    'data' => $location,
                    'message' => 'Berhasil mengubah lokasi'
                ]);
            }else{
                return response()->json([
                    'status' => 'success',
                    'data' => [],
                    'message' => 'Belum ada data lokasi'
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
                    Rule::unique('item_location')->ignore($data->id, 'id')->withoutTrashed()
                ],
                'code' => [
                    'required',
                    Rule::unique('item_location')->ignore($data->id, 'id')->withoutTrashed()
                ],
            ];
    
            $messages = [
                'id.required' => 'id tidak boleh kosong',
                'name.required' => 'Tidak boleh kosong',
                'name.unique' => 'Lokasi sudah terdaftar',
                'code.required' => 'Tidak boleh kosong',
                'code.unique' => 'Kode sudah terdaftar',
            ];
    
            $validator = Validator::make($data->all(), $rules, $messages);
            if($validator->fails()){
                return response()->json([
                    'status' => 'fail',
                    'data' => $validator->errors(),
                    'message' => $validator->errors()->first(),
                ]);
            }

            $update = ItemLocation::where('id', $data->id)
            ->update([
                'name' => $data->name,
                'code' => $data->code
            ]);

            if($update){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Berhasil mengubah lokasi'
                ]);
            }else{
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Gagal Mengubah lokasi'
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
            $delete = ItemLocation::where('id', $data->id)->delete();
            if($delete){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Berhasil Menghapus lokasi'
                ]);
            }else{
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Gagal Menghapus lokasi'
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