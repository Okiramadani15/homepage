<?php

namespace App\Services\Backoffice\Assets;

use Auth;
use App\Models\Backoffice\Assets\AssetBleaching;
use App\Models\Backoffice\Assets\Asset;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AssetBleachingService {
    public function index($data){
        try{
            $from = Carbon::now()->subDays(30)->format('Y-m-d');
            if($data->from){
                $from = $data->from;
            }
            
            $from .= ' ' . '00:00:00';

            $now = Carbon::now()->format('Y-m-d');
            if($data->now){
                $now = $data->now;
            }
            $now .= ' ' . '23:59:59';

            $from = strtotime($from);
            $now = strtotime($now);

            $bleaching = new AssetBleaching;
            $bleaching = $bleaching->orderBy('created_at', 'desc');
            $bleaching = $bleaching->with([
                'asset' => function($q){
                    $q->with(['type_asset'])->withTrashed();
                }, 
                'responsible', 
                'approve',
            ]);

            // if($data->type != "all"){
            //     $loan = $loan->where('type', $data->type);
            // }
            $bleaching = $bleaching->whereBetween('created_at', [$from, $now]);
            $bleaching = $bleaching->paginate($data->limit)->onEachSide(1)->toArray();
            
            $listPage = [];
            $i = 1;
            foreach($bleaching['links'] as $value){
                $explodeUrl = explode("page=", $value['url']);

                if($i == 1){
                    $tmpBleaching = [
                        'page' => $bleaching['current_page'] != 1 ? $bleaching['current_page'] - 1 : 1,
                        'label' => "Previous",
                        'active' => $value['active'],
                    ];
                }elseif($i == $bleaching['last_page'] + 2){
                    $tmpBleaching = [
                        'page' => $bleaching['current_page'] < $bleaching['last_page'] ? $bleaching['current_page'] + 1 : $bleaching['current_page'],
                        'label' => "Next",
                        'active' => $value['active'],
                    ];
                }elseif($value['url'] != null){
                    $tmpBleaching = [
                        'page' => $explodeUrl[1],
                        'label' => $value['label'],
                        'active' => $value['active'],
                    ];
                }else{
                    $tmpBleaching = [
                        'page' => "",
                        'label' => "...",
                        'active' => false
                    ];
                }
                array_push($listPage, $tmpBleaching);
                $i += 1;
            }
            
            if($bleaching){
                return response()->json([
                    'status' => 'success',
                    'pagination' => [
                        'current_page' => $bleaching['current_page'],
                        'last_page' => $bleaching['last_page'],
                        'previous_page' => $bleaching['current_page'] != 1 ? $bleaching['current_page'] - 1 : 1,
                        'next_page' => $bleaching['current_page'] < $bleaching['last_page'] ? $bleaching['current_page'] + 1 : $bleaching['current_page'],
                        'per_page' => $bleaching['per_page'],
                        'total' => $bleaching['total'],
                        'from' => $bleaching['from'],
                        'to' => $bleaching['to'],
                        'list_page' => $listPage,
                    ],
                    'data' => $bleaching['data'],
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
            $isExist = AssetBleaching::where('id_asset', $data->id)->first();
            if($isExist){
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Pemutihan sudah diajukan untuk asset ini!'
                ]);
            }

            $bleaching = new AssetBleaching;
            $bleaching->id_asset = $data->id;
            $bleaching->id_responsible = Auth::user()->id;
            $bleaching->created_at = round(microtime(true));
            $bleaching->updated_at = round(microtime(true));
            $bleaching->save();
            
            if($bleaching){
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

    public function changeStatus($data){
        DB::beginTransaction();
        try{
            $bleaching = AssetBleaching::where('id', $data->id)->first();
            $bleaching->status = $data->status;
            $bleaching->save();

            if($data->status == 3){
                $asset = Asset::where('id', $bleaching->id_asset)->delete();
            }

            if($bleaching){
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success'
                ]);
            }else{
                DB::rollback();
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Failed'
                ]);
            }
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }
}