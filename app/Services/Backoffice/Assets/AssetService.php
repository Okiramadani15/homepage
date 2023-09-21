<?php

namespace App\Services\Backoffice\Assets;

use App\Models\Backoffice\Assets\Asset;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AssetService {
    public function getAllAssets($data){
        try{
            $assets = new Asset;
            $assets = $assets->orderBy('name', 'asc');
            if(strtolower($data->type) != 'all' && $data->type != null){
                $assets = $assets->where('type', $data->type);
            }
            if(strtolower($data->location) != 'all' && $data->location != null){
                $assets = $assets->where('id_location', $data->location);
            }
            if(strtolower($data->work_unit) != 'all' && $data->work_unit != null){
                $assets = $assets->where('id_work_unit', $data->work_unit);
            }
            $assets = $assets->with(['location', 'work_unit', 'group_of_code', 'purchase_location']);
            $assets = $assets->where('name', 'like', '%'.$data->search.'%');
            $assets = $assets->paginate($data->limit)->onEachSide(1)->toArray();
            
            $listPage = [];
            $i = 1;
            foreach($assets['links'] as $value){
                $explodeUrl = explode("page=", $value['url']);

                if($i == 1){
                    $tmpAssets = [
                        'page' => $assets['current_page'] != 1 ? $assets['current_page'] - 1 : 1,
                        'label' => "Previous",
                        'active' => $value['active'],
                    ];
                }elseif($i == $assets['last_page'] + 2){
                    $tmpAssets = [
                        'page' => $assets['current_page'] < $assets['last_page'] ? $assets['current_page'] + 1 : $assets['current_page'],
                        'label' => "Next",
                        'active' => $value['active'],
                    ];
                }elseif($value['url'] != null){
                    $tmpAssets = [
                        'page' => $explodeUrl[1],
                        'label' => $value['label'],
                        'active' => $value['active'],
                    ];
                }else{
                    $tmpAssets = [
                        'page' => "",
                        'label' => "...",
                        'active' => false
                    ];
                }
                array_push($listPage, $tmpAssets);
                $i += 1;
            }
            
            if($assets){
                return response()->json([
                    'status' => 'success',
                    'pagination' => [
                        'current_page' => $assets['current_page'],
                        'last_page' => $assets['last_page'],
                        'previous_page' => $assets['current_page'] != 1 ? $assets['current_page'] - 1 : 1,
                        'next_page' => $assets['current_page'] < $assets['last_page'] ? $assets['current_page'] + 1 : $assets['current_page'],
                        'per_page' => $assets['per_page'],
                        'total' => $assets['total'],
                        'from' => $assets['from'],
                        'to' => $assets['to'],
                        'list_page' => $listPage,
                    ],
                    'data' => $assets['data'],
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

    public function listAsset($data){
        try{
            $asset = new Asset;
            if($data->type){
               $asset = $asset ->where('type', $data->type);
            }

            if($data->id_location && $data->id_location != 'all'){
                $asset = $asset ->where('id_location', $data->id_location);
            }
            $asset = $asset->orderBy('name', 'asc');
            $asset = $asset->select('id', 'name', 'id_location', 'total', 'quantity_loan');
            $asset = $asset->get();

            if($asset){
                return response()->json([
                    'status' => 'success',
                    'data' => $asset,
                    'message' => 'Success'
                ]);
            }else{
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Something went wrong'
                ]);
            }
        }catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function createAsset($data){
        try{
            $rules = [
                'name' => 'required',
                'date_of_purchase' => 'required',
                'total' => 'required',
                'price' => 'required',
                'group_code' => 'required',
                'id_purchase_location' => 'required',
                'id_location' => 'required',
                'id_work_unit' => 'required',
                'condition_good' => 'required',
                'condition_not_good' => 'required',
                'condition_very_bad' => 'required',
            ];
    
            $messages = [
                'name.required' => 'Nama aset tidak boleh kosong',
                'date_of_purchase.required' => 'Tanggal pembelian tidak Boleh Kosong',
                'total.required' => 'Total Aset tidak boleh Kosong',
                'price.required' => 'Harga pembelian tidak boleh kosong',
                'group_code.required' => 'Kode golongan tidak boleh kosong',
                'id_purchase_location.required' => 'Lokasi Pembelian tidak boleh kosong',
                'id_location.required' => 'Lokasi aset tidak boleh kosong',
                'id_work_unit.required' => 'Satuan kerja tidak boleh kosong',
                'condition_good.required' => 'Kondisi barang tidak boleh kosong',
                'condition_not_good.required' => 'Kondisi barang tidak boleh kosong',
                'condition_very_bad.required' => 'Kondisi barang tidak boleh kosong',
            ];
    
            $validator = Validator::make($data->all(), $rules, $messages);
            if($validator->fails()){
                return response()->json([
                    'status' => 'fail',
                    'data' => $validator->errors(),
                    'message' => $validator->errors()->first(),
                ]);
            }

            $countCondition = $data->condition_good + $data->condition_not_good + $data->condition_very_bad;
            if($countCondition != $data->total){
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Kondisi barang tidak sesuai dengan total barang',
                ]);
            }

            $asset = new Asset;
            $asset->name = $data->name;
            if($data->merk){
                $asset->merk = $data->merk;
            }
            if($data->serial_number){
                $asset->serial_number = $data->serial_number;
            }
            if($data->material){
                $asset->material = $data->material;
            }
            if($data->code){
                $asset->code = $data->code;
            }
            if($data->description){
                $asset->description = $data->description;
            }
            
            $fileName = '/assets/illustrations/no-image.png';
            
            $foto = $data->photo;
            if ($foto) {
                $fileName = '/assets/sarpass/' . '_' . time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('/assets/sarpass/'), $fileName);
            }
            
            $asset->group_code = $data->group_code;
            $asset->id_purchase_location = $data->id_purchase_location;
            $asset->type = $data->type;
            $asset->size = $data->size;
            $asset->date_of_purchase = $data->date_of_purchase;
            $asset->total = $data->total;
            $asset->price = $data->price;
            $asset->barcode = $data->barcode;
            $asset->id_location = $data->id_location;
            $asset->id_work_unit = $data->id_work_unit;
            $asset->photo = $fileName;

            if($data->condition_good){
                $asset->condition_good = $data->condition_good;
            }else{
                $asset->condition_good = 0;
            }
            if($data->condition_not_good){
                $asset->condition_not_good = $data->condition_not_good;
            }else{
                $asset->condition_not_good = 0;
            }
            if($data->condition_very_bad){
                $asset->condition_very_bad = $data->condition_very_bad;
            }else{
                $asset->condition_very_bad = 0;
            }

            $asset->save();
            
            if($asset){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Berhasil menambahkan data'
                ]);
            }else{
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Gagal menambahkan data'
                ]);
            }
        }catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function detailAsset($data){
        try{
            $detail = Asset::with(['location', 'work_unit', 'type_asset', 'group_of_code', 'purchase_location'])->find($data->id);
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

    public function updateAsset($data){
        try{
            $rules = [
                'name' => 'required',
                // 'size' => 'required',
                'date_of_purchase' => 'required',
                'total' => 'required',
                'price' => 'required',
                'group_code' => 'required',
                'id_purchase_location' => 'required',
                'id_location' => 'required',
                'id_work_unit' => 'required',
                'condition_good' => 'required',
                'condition_not_good' => 'required',
                'condition_very_bad' => 'required',
            ];
    
            $messages = [
                'name.required' => 'Nama Tidak Boleh Kosong',
                // 'size.required' => 'Ukuran Tidak Boleh Kosong',
                'date_of_purchase.required' => 'Tidak Boleh Kosong',
                'total.required' => 'Tidak Boleh Kosong',
                'price.required' => 'Tidak Boleh Kosong',
                'group_code.required' => 'Tidak Boleh Kosong',
                'id_purchase_location.required' => 'Tidak Boleh Kosong',
                'id_location.required' => 'Tidak Boleh Kosong',
                'id_work_unit.required' => 'Tidak Boleh Kosong',
                'condition_good.required' => 'Kondisi barang tidak boleh kosong',
                'condition_not_good.required' => 'Kondisi barang tidak boleh kosong',
                'condition_very_bad.required' => 'Kondisi barang tidak boleh kosong',
            ];
    
            $validator = Validator::make($data->all(), $rules, $messages);
            if($validator->fails()){
                return response()->json([
                    'status' => 'fail',
                    'data' => $validator->errors(),
                    'message' => $validator->errors()->first(),
                ]);
            }

            $countCondition = $data->condition_good + $data->condition_not_good + $data->condition_very_bad;
            if($countCondition != $data->total){
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Kondisi barang tidak sesuai dengan jumlah barang',
                ]);
            }

            $asset = Asset::find($data->id);
            $asset->name = $data->name;
            if($data->merk){
                $asset->merk = $data->merk;
            }
            if($data->serial_number){
                $asset->serial_number = $data->serial_number;
            }
            if($data->material){
                $asset->material = $data->material;
            }
            if($data->code){
                $asset->code = $data->code;
            }
            if($data->description){
                $asset->description = $data->description;
            }
            
            $fileName = $asset->photo;
            
            $foto = $data->photo;
            if ($foto) {
                $fileName = '/assets/sarpass/' . '_' . time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('/assets/sarpass/'), $fileName);
            }
            
            $asset->group_code = $data->group_code;
            $asset->id_purchase_location = $data->id_purchase_location;
            $asset->type = $data->type;
            $asset->size = $data->size;
            $asset->date_of_purchase = $data->date_of_purchase;
            $asset->total = $data->total;
            $asset->price = $data->price;
            $asset->barcode = $data->barcode;
            $asset->id_location = $data->id_location;
            $asset->id_work_unit = $data->id_work_unit;
            $asset->photo = $fileName;
            $asset->save();
            
            if($asset){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Berhasil mengubah data barang'
                ]);
            }else{
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Gagal menambahkan data'
                ]);
            }
        }catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteAsset($data){
        try{
            $delete = Asset::where('id', $data->id)->delete();
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

    public function exportAsset($data){
        try{
            $assets = new Asset;
            $assets = $assets->orderBy('name', 'asc');
            if(strtolower($data->type) != 'all' && $data->type != null){
                $assets = $assets->where('type', $data->type);
            }
            if(strtolower($data->location) != 'all' && $data->location != null){
                $assets = $assets->where('id_location', $data->location);
            }
            if(strtolower($data->work_unit) != 'all' && $data->work_unit != null){
                $assets = $assets->where('id_work_unit', $data->work_unit);
            }
            $assets = $assets->with(['location', 'work_unit', 'group_of_code', 'purchase_location']);
            $assets = $assets->where('name', 'like', '%'.$data->search.'%');
            $assets = $assets->select('id', 'id_location', 'name', 'merk', 'serial_number', 'size', 'material', 'date_of_purchase', 'code', 'total', 'price', 'condition_good', 'condition_not_good', 'condition_very_bad', 'id_work_unit', 'id_purchase_location')->get();

            $data = [];
            foreach($assets as $value){
                $tmpAsset = [
                    'Nama' => $value->name,
                    'Merk' => $value->merk,
                    'NoSeri' => $value->serial_number,
                    'Material' => $value->material,
                    'Kode Barang' => $value->asset_code,
                    'Tanggal Pembelian' => $value->date_of_purchase,
                    'Tanggal Pembelian' => $value->date_of_purchase,
                    'Total' => $value->total,
                    'harga' => $value->price,
                    'Kondisi Baik' => $value->condition_good,
                    'Kondisi Kurang Baik' => $value->condition_not_good,
                    'Kondisi Rusak' => $value->condition_very_bad,
                    'Lokasi' => $value->location ? $value->location : "-",
                    'Satuan Kerja' => $value->work_unit ? $value->work_unit->name : "-",
                    'Sumber Pembelian' => $value->purchase_location ? $value->purchase_location->name : "-",
                ];

                array_push($data, $tmpAsset);
            }
            return $data;

        }catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }
}
