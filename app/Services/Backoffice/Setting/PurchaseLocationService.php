<?php

namespace App\Services\Backoffice\Setting;

use App\Models\Backoffice\Setting\PurchaseLocation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PurchaseLocationService{
    public function index($data){
        try{
            $purchaseLocation = PurchaseLocation::orderBy('name', 'asc')->get();

            if($purchaseLocation){
                return response()->json([
                    'status' => 'success',
                    'data' => $purchaseLocation,
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
            
            $purchaseLocation = new PurchaseLocation;
            $purchaseLocation = $purchaseLocation->orderBy('id', 'asc');
            $purchaseLocation = $purchaseLocation->where('name', 'like', '%'.$data->search.'%');
            $purchaseLocation = $purchaseLocation->paginate($data->limit)->onEachSide(1)->toArray();
            
            $listPage = [];
            $i = 1;

            foreach($purchaseLocation['links'] as $value){
                $explodeUrl = explode("page=", $value['url']);

                if($i == 1){
                    $tmpPurchaseLocation = [
                        'page' => $purchaseLocation['current_page'] != 1 ? $purchaseLocation['current_page'] - 1 : 1,
                        'label' => "Previous",
                        'active' => $value['active'],
                    ];
                }elseif($i == $purchaseLocation['last_page'] + 2){
                    $tmpPurchaseLocation = [
                        'page' => $purchaseLocation['current_page'] < $purchaseLocation['last_page'] ? $purchaseLocation['current_page'] + 1 : $purchaseLocation['current_page'],
                        'label' => "Next",
                        'active' => $value['active'],
                    ];
                }elseif($value['url'] != null){
                    $tmpPurchaseLocation = [
                        'page' => $explodeUrl[1],
                        'label' => $value['label'],
                        'active' => $value['active'],
                    ];
                }else{
                    $tmpPurchaseLocation = [
                        'page' => "",
                        'label' => "...",
                        'active' => false
                    ];
                }
                array_push($listPage, $tmpPurchaseLocation);
                $i += 1;
            }

            if($purchaseLocation){
                return response()->json([
                    'status' => 'success',
                    'pagination' => [
                        'current_page' => $purchaseLocation['current_page'],
                        'last_page' => $purchaseLocation['last_page'],
                        'previous_page' => $purchaseLocation['current_page'] != 1 ? $purchaseLocation['current_page'] - 1 : 1,
                        'next_page' => $purchaseLocation['current_page'] < $purchaseLocation['last_page'] ? $purchaseLocation['current_page'] + 1 : $purchaseLocation['current_page'],
                        'per_page' => $purchaseLocation['per_page'],
                        'total' => $purchaseLocation['total'],
                        'from' => $purchaseLocation['from'],
                        'to' => $purchaseLocation['to'],
                        'list_page' => $listPage,
                    ],
                    'data' => $purchaseLocation['data'],
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
                    Rule::unique('purchase_location', 'name')->withoutTrashed()
                ],
                'code' => [
                    'required',
                    Rule::unique('purchase_location', 'code')->withoutTrashed()
                ],
            ];
    
            $messages = [
                'name.required' => 'Lokasi Pembelian Tidak Boleh Kosong',
                'name.unique' => 'Lokasi Pembelian sudah terdaftar',
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

            $purchaseLocation = PurchaseLocation::create([
                'name' => $data->name,
                'code' => $data->code,
            ]);
            
            if($purchaseLocation){
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
            $purchaseLocation = PurchaseLocation::find($data->id);

            if($purchaseLocation){
                return response()->json([
                    'status' => 'success',
                    'data' => $purchaseLocation,
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
                    Rule::unique('purchase_location')->ignore($data->id, 'id')->withoutTrashed()
                ],
                'code' => [
                    'required',
                    Rule::unique('purchase_location')->ignore($data->id, 'id')->withoutTrashed()
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

            $update = PurchaseLocation::where('id', $data->id)
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
            $delete = PurchaseLocation::where('id', $data->id)->delete();

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