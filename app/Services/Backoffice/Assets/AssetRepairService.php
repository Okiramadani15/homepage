<?php

namespace App\Services\Backoffice\Assets;

use Auth;
use App\Models\Backoffice\Assets\Repair;
use App\Models\Backoffice\Assets\DetailRepair;
use App\Models\Backoffice\Assets\RepairImageReport;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AssetRepairService {
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

            $repair = new Repair;
            $repair = $repair->orderBy('created_at', 'desc');
            $repair = $repair->with([
                'responsible' => function($q){
                    $q->select('id', 'name');
                },
                'approval' => function($q){
                    $q->select('id', 'name');
                },
                'status_repair' => function($q){
                    $q->select('id', 'key', 'name');
                }
            ]);

            if($data->type && $data->type != "all"){
                $repair = $repair->where('type', $data->type);
            }

            $repair = $repair->whereBetween('created_at', [$from, $now]);
            $repair = $repair->paginate($data->limit)->onEachSide(1)->toArray();
            
            $listPage = [];
            $i = 1;
            foreach($repair['links'] as $value){
                $explodeUrl = explode("page=", $value['url']);

                if($i == 1){
                    $tmpRepair = [
                        'page' => $repair['current_page'] != 1 ? $repair['current_page'] - 1 : 1,
                        'label' => "Previous",
                        'active' => $value['active'],
                    ];
                }elseif($i == $repair['last_page'] + 2){
                    $tmpRepair = [
                        'page' => $repair['current_page'] < $repair['last_page'] ? $repair['current_page'] + 1 : $repair['current_page'],
                        'label' => "Next",
                        'active' => $value['active'],
                    ];
                }elseif($value['url'] != null){
                    $tmpRepair = [
                        'page' => $explodeUrl[1],
                        'label' => $value['label'],
                        'active' => $value['active'],
                    ];
                }else{
                    $tmpRepair = [
                        'page' => "",
                        'label' => "...",
                        'active' => false
                    ];
                }
                array_push($listPage, $tmpRepair);
                $i += 1;
            }
            
            if($repair){
                return response()->json([
                    'status' => 'success',
                    'pagination' => [
                        'current_page' => $repair['current_page'],
                        'last_page' => $repair['last_page'],
                        'previous_page' => $repair['current_page'] != 1 ? $repair['current_page'] - 1 : 1,
                        'next_page' => $repair['current_page'] < $repair['last_page'] ? $repair['current_page'] + 1 : $repair['current_page'],
                        'per_page' => $repair['per_page'],
                        'total' => $repair['total'],
                        'from' => $repair['from'],
                        'to' => $repair['to'],
                        'list_page' => $listPage,
                    ],
                    'data' => $repair['data'],
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
        DB::beginTransaction();
        try{
            $foto = $data->photo;
            if(!$foto){
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Dokumen/Foto tidak boleh kosong'
                ]);
            }

            $repair = new Repair;
            $repair->id_user_responsible = Auth::user()->id;
            $repair->id_status = 1;
            $repair->type = $data->type;
            $repair->reason = $data->reason;
            $repair->save();
            
            if ($foto) {
                $fileName = '/assets/image-report/before_repair' . '_' . time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('/assets/image-report/'), $fileName);

                $reportImage = new RepairImageReport;
                $reportImage->id_repair = $repair->id;
                $reportImage->image_before = $fileName;
                $reportImage->save();
            }
            
            $i = 0;
            foreach($data->id_asset as $value){
                $dataIdAsset = explode(",", $data->id_asset[0]);
                $dataQuantity = explode(",", $data->quantity[0]);
                $dataPrice = explode(",", $data->price[0]);

                $detail = new DetailRepair;
                $detail->id_repair = $repair->id;
                $detail->type = $data->type;
                $detail->id_asset = $dataIdAsset[$i];
                $detail->quantity = $dataQuantity[$i];
                $detail->price = $dataPrice[$i];
                $detail->id_status = 1;
                $detail->save();
                $i += 1;
            }
            
            if($repair && $reportImage && $detail){
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

    public function detail($data){
        try{
            $detail = Repair::with([
                'responsible' => function($q){
                    $q->with([
                        'position' => function($q){
                            $q->select('id', 'name');
                        }
                    ])
                    ->select('id', 'name', 'id_position', 'phone');
                },
                'approval' => function($q){
                    $q->select('id', 'name');
                },
                'status_repair' => function($q){
                    $q->select('id', 'key', 'name');
                },
                'detail' => function($q){
                    $q->with(['asset'])->get();
                },
                'image_report',
            ])
            ->where('id', $data->id)
            ->first();

            if($detail){
                return response()->json([
                    'status' => 'success',
                    'data' => $detail,
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

    public function update($data){
        DB::beginTransaction();
        try{
            $repair = Repair::where('id', $data->id)->first();
            $repair->reason = $data->reason;
            $repair->save();
            
            $foto = $data->photo;
            if ($foto) {
                $fileName = '/assets/image-report/before_repair' . '_' . time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('/assets/image-report/'), $fileName);

                $reportImage = RepairImageReport::where('id_repair', $data->id)->first();
                $reportImage->image_before = $fileName;
                $reportImage->save();
            }

            if($data->id_detail_repair){
                $idOldDetail = [];
                foreach($data->id_detail_repair as $value){
                    array_push($idOldDetail, $value);
                }
                $oldDetailRepair = DetailRepair::where('id_repair', $data->id)->whereNotIn('id', $idOldDetail)->delete();
            }
            
            if($data->id_asset){
                $i = 0;
                foreach($data->id_asset as $value){
                    $detail = new DetailRepair;
                    $detail->id_repair = $repair->id;
                    $detail->type = $repair->type;
                    $detail->id_asset = $data['id_asset'][$i];
                    $detail->quantity = $data['quantity'][$i];
                    $detail->price = $data['price'][$i];
                    $detail->id_status = 1;
                    $detail->save();

                    $i += 1;
                }
            }
            
            if($repair){
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

    public function changeStatus($data){
        try{
            if($data->id_status == 5){
                if(!$data->photo){
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'Laporan gambar tidak boleh kosong!'
                    ]);
                }

                if(!$data->bill){
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'bon (bukti pembayaran) tidak boleh kosong!'
                    ]);
                }
            }

            $repair = Repair::where('id', $data->id)->first();
            $repair->id_status = $data->id_status;
            $repair->reason_reject = $data->reason_reject;
            if($data->id_status == 3){
                $repair->id_user_approval = Auth::user()->id;
            }
            $repair->save();

            if($data->id_status == 5){
                $foto = $data->photo;
                $bill = $data->bill;
                $fileName = '';
                $billName = '';
                if ($foto && $bill) {
                    $fileName = '/assets/image-report/after-repair' . '_' . time() . '.' . $foto->getClientOriginalExtension();
                    $foto->move(public_path('/assets/image-report/'), $fileName);

                    $billName = '/assets/image-report/bill-repair' . '_' . time() . '.' . $bill->getClientOriginalExtension();
                    $bill->move(public_path('/assets/image-report/'), $billName);
                    
                    $reportImage = RepairImageReport::where('id_repair', $data->id)->first();
                    $reportImage->image_after = $fileName;
                    $reportImage->bill = $billName;
                    $reportImage = $reportImage->save();
                }
            }

            if($repair){
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

    public function delete($data){
        try{
            $delete = Repair::where('id', $data->id)->delete();

            if($delete){
                return response()->json([
                    'status' => 'success',
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
}