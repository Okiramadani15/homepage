<?php

namespace App\Services\Backoffice\Assets;

use Auth;
use App\Models\Backoffice\Assets\Loan;
use App\Models\Backoffice\Assets\DetailLoan;
use App\Models\Backoffice\Assets\Asset;
use App\Models\Backoffice\Assets\LoanReportImage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AssetLoanService {

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

            $loan = new Loan;
            $loan = $loan->orderBy('created_at', 'desc');
            $loan = $loan->with([
                'responsible' => function($q){
                    $q->select('id', 'name');
                },
                'approval' => function($q){
                    $q->select('id', 'name');
                },
                'status_loan' => function($q){
                    $q->select('id', 'key', 'name');
                }
            ]);

            if($data->type != "all"){
                $loan = $loan->where('type', $data->type);
            }
            $loan = $loan->whereBetween('created_at', [$from, $now]);
            $loan = $loan->paginate($data->limit)->onEachSide(1)->toArray();
            
            $listPage = [];
            $i = 1;
            foreach($loan['links'] as $value){
                $explodeUrl = explode("page=", $value['url']);

                if($i == 1){
                    $tmpLoan = [
                        'page' => $loan['current_page'] != 1 ? $loan['current_page'] - 1 : 1,
                        'label' => "Previous",
                        'active' => $value['active'],
                    ];
                }elseif($i == $loan['last_page'] + 2){
                    $tmpLoan = [
                        'page' => $loan['current_page'] < $loan['last_page'] ? $loan['current_page'] + 1 : $loan['current_page'],
                        'label' => "Next",
                        'active' => $value['active'],
                    ];
                }elseif($value['url'] != null){
                    $tmpLoan = [
                        'page' => $explodeUrl[1],
                        'label' => $value['label'],
                        'active' => $value['active'],
                    ];
                }else{
                    $tmpLoan = [
                        'page' => "",
                        'label' => "...",
                        'active' => false
                    ];
                }
                array_push($listPage, $tmpLoan);
                $i += 1;
            }
            
            if($loan){
                return response()->json([
                    'status' => 'success',
                    'pagination' => [
                        'current_page' => $loan['current_page'],
                        'last_page' => $loan['last_page'],
                        'previous_page' => $loan['current_page'] != 1 ? $loan['current_page'] - 1 : 1,
                        'next_page' => $loan['current_page'] < $loan['last_page'] ? $loan['current_page'] + 1 : $loan['current_page'],
                        'per_page' => $loan['per_page'],
                        'total' => $loan['total'],
                        'from' => $loan['from'],
                        'to' => $loan['to'],
                        'list_page' => $listPage,
                    ],
                    'data' => $loan['data'],
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
            $loan = new Loan;
            $loan->id_user_responsible = Auth::user()->id;
            $loan->id_status = 1;
            $loan->reason = $data->reason;
            $loan->type = $data->type;
            $loan->from = $data->from;
            $loan->to = $data->to;
            $loan->save();
            
            foreach($data->data as $value){
                $detail = new DetailLoan;
                $detail->id_loan = $loan->id;
                $detail->type = $loan->type;
                $detail->id_asset = $value['id_asset'];
                $detail->id_location = $value['id_location_now'];
                $detail->quantity = $value['quantity_loan'];
                $detail->save();
            }
            
            if($loan && $detail){
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

    public function delete($data){
        try{
            $delete = Loan::where('id', $data->id)->delete();
            
            if($delete){
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
            $detail = Loan::with([
                'responsible' => function($q){
                    $q->with([
                        'position' => function($q){
                            $q->select('id', 'name');
                        }
                    ])->select('id', 'name', 'id_position', 'phone');
                },
                'approval' => function($q){
                    $q->select('id', 'name');
                },
                'status_loan' => function($q){
                    $q->select('id', 'key', 'name');
                },
                'detail' => function($q){
                    $q->
                    with([
                        'location_now' => function($q){
                            $q->select('id', 'name');
                        },
                        'asset' => function($q){
                            $q->select('id', 'name', 'id_location', 'total');
                        }
                    ])
                    ->select(
                        'id',
                        'id_loan',
                        'id_asset',
                        'id_location',
                        'quantity_received',
                        'quantity',
                    );
                },
                'image_report'
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
            $loan = Loan::where('id', $data->id)->first();
            $loan->id_status = $data->id_status;
            $loan->reason_reject = $data->reason_reject;
            $loan->id_user_approval = Auth::user()->id;
            $loan->save();

            if($data->id_status == 2){
                $allDetail = DetailLoan::where('id_loan', $data->id)->get();

                foreach($allDetail as $value){
                    $asset = Asset::where('id', $value->id_asset)->first();
                    $asset->quantity_loan = $value->quantity + $asset->quantity_loan;
                    $asset->save();
                }

                $foto = $data->photo;
                $fileName = '';
                if ($foto) {
                    $fileName = '/assets/image-report/before-loan' . '_' . time() . '.' . $foto->getClientOriginalExtension();
                    $foto->move(public_path('/assets/image-report/'), $fileName);

                    $reportImage = LoanReportImage::where('id_loan', $data->id)->first();
                    if(!$reportImage){
                        $reportImage = new LoanReportImage;
                    }
                    $reportImage->id_loan = $data->id;
                    $reportImage->image_before = $fileName;
                    $reportImage = $reportImage->save();
                }

            }

            if($data->id_status == 5){
                $foto = $data->photo;
                $fileName = '';
                if ($foto) {
                    $fileName = '/assets/image-report/after-loan' . '_' . time() . '.' . $foto->getClientOriginalExtension();
                    $foto->move(public_path('/assets/image-report/'), $fileName);
                    
                    $reportImage = LoanReportImage::where('id_loan', $data->id)->first();
                    $reportImage->id_loan = $data->id;
                    $reportImage->image_after = $fileName;
                    $reportImage = $reportImage->save();
                }

            }

            if($loan){
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

    public function update($data) {
        DB::beginTransaction();
        try{
            $loan = Loan::where('id', $data->id)->first();
            $loan->reason = $data->reason;
            $loan->from = $data->from;
            $loan->to = $data->to;
            $loan->save();

            $idOldDetail = [];
            foreach($data->data as $value){
                array_push($idOldDetail, $value['id_detail_loan']);
            }
            
            $oldDetailLoan = DetailLoan::where('id_loan', $data->id)->whereNotIn('id', $idOldDetail)->delete();
            
            foreach($data->data as $value){
                if($value['id_detail_loan'] != 0){
                    $detail = DetailLoan::where('id', $value['id_detail_loan'])->first();
                    $detail->id_location = $value['id_location_now'];
                    $detail->quantity = $value['quantity_loan'];
                    $detail->save();
                }else{
                    $detail = new DetailLoan;
                    $detail->id_loan = $data->id;
                    $detail->type = $loan->type;
                    $detail->id_asset = $value['id_asset'];
                    $detail->id_location = $value['id_location_now'];
                    $detail->quantity = $value['quantity_loan'];
                    $detail->save();
                }
            }

            if($loan && $detail){
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

    public function updateDetailLoan($data){
        try{
            $detail = DetailLoan::find($data->id);
            
            if(($data->quantity_received + $detail->quantity_received) > $detail->quantity){
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Barang diterima tidak sesuai dengan quantity barang'
                ]);
            }

            $detail->quantity_received = $detail->quantity_received + $data->quantity_received;
            $detail->save();

            $asset = Asset::where('id', $detail->id_asset)->first();
            $asset->quantity_loan = $asset->quantity_loan - $data->quantity_received; 
            $asset->save();

            if($detail && $asset){
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

    public function onDecline($data){
        try{
            $loan = Loan::where('id', $data->id)->first();
            $loan->id_status = $data->id_status;
            $loan->reason_reject = $data->reason_reject;
            $loan->id_user_approval = Auth::user()->id;
            $loan->save();

            if($loan){
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