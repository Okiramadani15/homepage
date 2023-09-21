<?php

namespace App\Services\Backoffice;

use App\Models\Backoffice\EducationalCalendar;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon;

class EducationalCalendarService{
    public function index($data){
        try{
            $limit = 10;
            if($data->limit){
                $limit = $data->limit;
            }
            
            $calendar = new EducationalCalendar;
            $calendar = $calendar->orderBy('from', 'asc');
            $calendar = $calendar->with([
                'semester' => function($q){
                    $q->select('id', 'name');
                }]
            );
            $calendar = $calendar->where('description', 'like', '%'.$data->search.'%');
            $calendar = $calendar->paginate($data->limit)->onEachSide(1)->toArray();
            
            $listPage = [];
            $i = 1;

            foreach($calendar['links'] as $value){
                $explodeUrl = explode("page=", $value['url']);

                if($i == 1){
                    $tmpCalendar = [
                        'page' => $calendar['current_page'] != 1 ? $calendar['current_page'] - 1 : 1,
                        'label' => "Previous",
                        'active' => $value['active'],
                    ];
                }elseif($i == $calendar['last_page'] + 2){
                    $tmpCalendar = [
                        'page' => $calendar['current_page'] < $calendar['last_page'] ? $calendar['current_page'] + 1 : $calendar['current_page'],
                        'label' => "Next",
                        'active' => $value['active'],
                    ];
                }elseif($value['url'] != null){
                    $tmpCalendar = [
                        'page' => $explodeUrl[1],
                        'label' => $value['label'],
                        'active' => $value['active'],
                    ];
                }else{
                    $tmpCalendar = [
                        'page' => "",
                        'label' => "...",
                        'active' => false
                    ];
                }
                array_push($listPage, $tmpCalendar);
                $i += 1;
            }

            if($calendar){
                return response()->json([
                    'status' => 'success',
                    'pagination' => [
                        'current_page' => $calendar['current_page'],
                        'last_page' => $calendar['last_page'],
                        'previous_page' => $calendar['current_page'] != 1 ? $calendar['current_page'] - 1 : 1,
                        'next_page' => $calendar['current_page'] < $calendar['last_page'] ? $calendar['current_page'] + 1 : $calendar['current_page'],
                        'per_page' => $calendar['per_page'],
                        'total' => $calendar['total'],
                        'from' => $calendar['from'],
                        'to' => $calendar['to'],
                        'list_page' => $listPage,
                    ],
                    'data' => $calendar['data'],
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
            $calendar = EducationalCalendar::create([
                'description' => $data->description,
                'from' => $data->from,
                'to' => $data->to,
                'id_semester' => $data->id_semester,
            ]);
            
            if($calendar){
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
            $calendar = EducationalCalendar::with([
                'semester' => function($q){
                    $q->select('id', 'name');
                }
            ])->find($data->id);

            if($calendar){
                return response()->json([
                    'status' => 'success',
                    'data' => $calendar,
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

    public function update($data){
        try{
            $calendar = EducationalCalendar::find($data->id);
            $calendar->description = $data->description;
            $calendar->from = $data->from;
            $calendar->to = $data->to;
            $calendar->id_semester = $data->id_semester;
            $calendar->save();
            
            if($calendar){
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
            $calendar = EducationalCalendar::where('id', $data->id)->delete();
            
            if($calendar){
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
}