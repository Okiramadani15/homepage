<?php

namespace App\Services\Backoffice\Assets;

use Auth;
use App\Models\Backoffice\Assets\Procurement;
use App\Models\Backoffice\Assets\DetailProcurement;
use App\Models\Backoffice\Assets\Asset;
use App\Models\Backoffice\Assets\CompletenessDocument;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AssetProcurementService {

    public function index($data){
        try{
            $limit = 10;
            if($data->limit){
                $limit = $data->limit;
            }

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

            $procurement = new Procurement;
            $procurement = $procurement->with([
                'responsible' => function($q){
                    $q->select('id', 'name');
                },
                'approval' => function($q){
                    $q->select('id', 'name');
                },
                'status_procurement' => function($q){
                    $q->select('id', 'key', 'name');
                },
                'type_procurement'
            ]);
            
            if($data->type && $data->type != "all"){
                $procurement = $procurement->where('type', $data->type);
            }

            $procurement = $procurement->orderBy('created_at', 'desc');
            $procurement = $procurement->whereBetween('created_at', [$from, $now]);
            $procurement = $procurement->paginate($data->limit)->onEachSide(1)->toArray();
            
            $listPage = [];
            $i = 1;

            foreach($procurement['links'] as $value){
                $explodeUrl = explode("page=", $value['url']);

                if($i == 1){
                    $tmpProcurement = [
                        'page' => $procurement['current_page'] != 1 ? $procurement['current_page'] - 1 : 1,
                        'label' => "Previous",
                        'active' => $value['active'],
                    ];
                }elseif($i == $procurement['last_page'] + 2){
                    $tmpProcurement = [
                        'page' => $procurement['current_page'] < $procurement['last_page'] ? $procurement['current_page'] + 1 : $procurement['current_page'],
                        'label' => "Next",
                        'active' => $value['active'],
                    ];
                }elseif($value['url'] != null){
                    $tmpProcurement = [
                        'page' => $explodeUrl[1],
                        'label' => $value['label'],
                        'active' => $value['active'],
                    ];
                }else{
                    $tmpProcurement = [
                        'page' => "",
                        'label' => "...",
                        'active' => false
                    ];
                }
                array_push($listPage, $tmpProcurement);
                $i += 1;
            }

            if($procurement){
                return response()->json([
                    'status' => 'success',
                    'pagination' => [
                        'current_page' => $procurement['current_page'],
                        'last_page' => $procurement['last_page'],
                        'previous_page' => $procurement['current_page'] != 1 ? $procurement['current_page'] - 1 : 1,
                        'next_page' => $procurement['current_page'] < $procurement['last_page'] ? $procurement['current_page'] + 1 : $procurement['current_page'],
                        'per_page' => $procurement['per_page'],
                        'total' => $procurement['total'],
                        'from' => $procurement['from'],
                        'to' => $procurement['to'],
                        'list_page' => $listPage,
                    ],
                    'data' => $procurement['data'],
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
            $procurement = new Procurement;
            $procurement->id_user_responsible = Auth::user()->id;
            $procurement->id_status = 1;
            $procurement->type = $data->procurement_type;
            $procurement->reason = $data->reason;
            $procurement->save();

            $i = 0;
            foreach($data->name as $value){
                $detail = new DetailProcurement;
                $detail->id_procurement = $procurement->id;
                $detail->name = $data['name'][$i];
                $detail->quantity = $data['quantity'][$i];
                $detail->price = $data['price'][$i];
                $detail->id_status = 1;
                $detail->year = $data['year'][$i];
                $detail->type = $data['type'][$i];
                $detail->location = $data['location'][$i];
                $detail->unit = $data['unit'][$i];

                if($data['type'][$i] == 1){
                    $documentRabName = '/assets/illustrations/no-image.png';
                    $documentRab = $data['document_rab'][$i];
                    if ($documentRab) {
                        $documentRabName = '/assets/procurement/' . 'rab_' . time() . '.' . $documentRab->getClientOriginalExtension();
                        $documentRab->move(public_path('/assets/procurement/'), $documentRabName);
                    }
                    $detail->document_rab = $documentRabName;
                }
                $detail->save();
                $i += 1;
            }

            if($procurement && $detail){
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success'
                ]);
            }else{
                DB::rollback();
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Something went wrong'
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
            $delete = Procurement::where('id', $data->id)->delete();

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

    public function detail($data){
        try{
            $detail = Procurement::with([
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
                'status_procurement' => function($q){
                    $q->select('id', 'key', 'name');
                },
                'detail' => function($q){
                    $q->with([
                        'status' => function($q){
                            $q->select('id', 'key', 'name');
                        },
                        'type',
                        'location',
                        'unit',
                        'document',
                    ])
                    ->select('id', 'id_procurement', 'name', 'quantity', 'quantity_received', 'price', 'document_rab', 'id_status', 'year', 'type', 'location', 'unit', 'id_asset');
                },
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
            $procurement = Procurement::find($data->id_procurement);
            $procurement->reason = $data->reason;
            $procurement->save();

            $oldDetailProcurement = DetailProcurement::where('id_procurement', $data->id_procurement)->whereNotIn('id', $data->id)->delete();
            
            $i = 0;
            $j = 0;
            foreach($data->id as $value){
                if($value != 0){
                    $detailProcurement = DetailProcurement::where([
                        'id' => $value,
                        'id_procurement' => $data->id_procurement,
                    ])->first();

                    $detailProcurement->name = $data['name'][$i];
                    $detailProcurement->quantity = $data['quantity'][$i];
                    $detailProcurement->price = $data['price'][$i];
                    $detailProcurement->year = $data['year'][$i];
                    $detailProcurement->type = $data['type'][$i];
                    $detailProcurement->location = $data['location'][$i];
                    $detailProcurement->unit = $data['unit'][$i];
                    $detailProcurement->save();
                }else{
                    $detailProcurement = new DetailProcurement;
                    $detailProcurement->id_procurement = $data->id_procurement;
                    $detailProcurement->name = $data['name'][$i];
                    $detailProcurement->quantity = $data['quantity'][$i];
                    $detailProcurement->price = $data['price'][$i];
                    $detailProcurement->year = $data['year'][$i];
                    $detailProcurement->type = $data['type'][$i];
                    $detailProcurement->location = $data['location'][$i];
                    $detailProcurement->unit = $data['unit'][$i];
                    $detailProcurement->id_status = 1;
                    
                    if($data['type'][$i] == 1){
                        $documentRab = $data['document_rab'][$j];
                        if ($documentRab) {
                            $documentRabName = '/assets/illustrations/no-image.png';
                            $documentRabName = '/assets/procurement/' . 'rab_' . time() . '.' . $documentRab->getClientOriginalExtension();
                            $documentRab->move(public_path('/assets/procurement/'), $documentRabName);
                            $detailProcurement->document_rab = $documentRabName;
                        }
                        $j += 1;
                    }
                    $detailProcurement->save();
                }

                $i += 1;
            }

            if($detailProcurement && $procurement){
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success'
                ]);
            }else{
                DB::rollback();
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Something went wrong'
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

    public function onProcess($data){
        try{
            $proessProcurement = Procurement::find($data->id);
            $proessProcurement->id_status = 2;
            $proessProcurement->save();

            if($proessProcurement){
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

    public function onApprove($data){
        try{
            $proessProcurement = Procurement::find($data->id);
            $proessProcurement->id_status = 3;
            $proessProcurement->id_user_approved = Auth::user()->id;
            $proessProcurement->save();

            if($proessProcurement){
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
            $procurement = Procurement::find($data->id);
            $procurement->id_status = 4;
            $procurement->reason_reject = $data->reason_reject;
            $procurement->save();

            if($procurement){
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

    public function onCompleted($data){
        DB::beginTransaction();
        try{
            $procurement = Procurement::find($data->id);
            $procurement->id_status = 5;
            $procurement->save();

            $detail = DetailProcurement::where('id_procurement', $data->id)->get();

            foreach($detail as $value){
                $detailDocument = CompletenessDocument::where('id_detail', $value->id)->first();

                $asset = new Asset;
                $asset->name = $value->name;
                $asset->total = $value->quantity;
                $asset->price = $value->price;
                $asset->date_of_purchase = $value->year;
                $asset->type = $value->type;
                $asset->id_location = $value->location;
                $asset->id_work_unit = $value->unit;
                $asset->condition_good = $value->quantity;
                $asset->condition_not_good = 0;
                $asset->condition_very_bad = 0;
                $asset->quantity_loan = 0;
                $asset->photo = $detailDocument->photo;
                $asset->save();

                $detailProcurement = DetailProcurement::where('id', $value->id)->first();
                $detailProcurement->id_asset = $asset->id;
                $detailProcurement->save();
            }

            if($procurement && $asset){
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success'
                ]);
            }else{
                DB::rollback();
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Something went wrong'
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
            $procurement = Procurement::find($data->id);
            $procurement->id_status = $data->id_status;
            $procurement->id_user_approved = Auth::user()->id;
            $procurement->reason_reject = $data->reason_reject;
            $procurement->save();

            if($procurement){
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

    public function updateDetailProcurement($data){
        try{
            $detail = DetailProcurement::find($data->id);

            if(($data->quantity_received + $detail->quantity_received) > $detail->quantity){
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Barang diterima tidak sesuai dengan quantity barang'
                ]);
            }

            $detail->quantity_received = $detail->quantity_received + $data->quantity_received;
            $detail->save();

            if($detail){
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

    public function finishDetailProcurement($data){
        DB::beginTransaction();
        try{
            $detail = DetailProcurement::find($data->id);
            $detail->id_status = 3;
            $detail->save();

            $document = new CompletenessDocument;
            $document->id_completeness = $detail->id_procurement;
            $document->id_detail = $data->id;

            $foto = $data->image;
            if ($foto) {
                $imageName = '/assets/illustrations/no-image.png';
                $imageName = '/assets/procurement/' . 'photo_' . time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('/assets/procurement/'), $imageName);
                $document->photo = $imageName;
            }

            $documentProcurement = $data->document;
            if ($documentProcurement) {
                $documentProcurementName = '/assets/illustrations/no-image.png';
                $documentProcurementName = '/assets/procurement/' . 'bill_' . time() . '.' . $documentProcurement->getClientOriginalExtension();
                $documentProcurement->move(public_path('/assets/procurement/'), $documentProcurementName);
                $document->document = $documentProcurementName;
            }

            $document->save();

            if($detail && $document){
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success'
                ]);
            }else{
                DB::rollback();
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Something went wrong'
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

    public function documentProcurement($data){
        try{
            $document = CompletenessDocument::with([
                'detail'
            ])->where('id_completeness', $data->id)
            ->get();
            
            if($document){
                return response()->json([
                    'status' => 'success',
                    'data' => $document,
                    'message' => 'Success'
                ]);
            }else{
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Data not found'
                ]);
            }
        }catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function exportProcurement($data){
        // retreive all records from db
        // $data = Procurement::get();
        // // share data to view
        // view()->share('employee',$data);
        // $pdf = PDF::loadView('pdf_view', [$data]);
        // // download PDF file with download method
        // return $pdf->download('pdf_file.pdf');
        return "OK";
      
    }
}