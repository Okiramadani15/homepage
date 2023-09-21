<?php

namespace App\Services\Homepage;

use App\Models\Backoffice\Activity;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth;

class ActivityService{
    public function index($data){
        try{
            $limit = 10;
            if($data->limit){
                $limit = $data->limit;
            }
            
            $activity = new Activity;
            $activity = $activity->orderBy('created_at', 'asc');
            $activity = $activity->where('title', 'like', '%'.$data->search.'%');
            $activity = $activity->paginate($data->limit)->onEachSide(1)->toArray();
            
            $listPage = [];
            $i = 1;

            foreach($activity['links'] as $value){
                $explodeUrl = explode("page=", $value['url']);

                if($i == 1){
                    $tmpActivity = [
                        'page' => $activity['current_page'] != 1 ? $activity['current_page'] - 1 : 1,
                        'label' => "Previous",
                        'active' => $value['active'],
                    ];
                }elseif($i == $activity['last_page'] + 2){
                    $tmpActivity = [
                        'page' => $activity['current_page'] < $activity['last_page'] ? $activity['current_page'] + 1 : $activity['current_page'],
                        'label' => "Next",
                        'active' => $value['active'],
                    ];
                }elseif($value['url'] != null){
                    $tmpActivity = [
                        'page' => $explodeUrl[1],
                        'label' => $value['label'],
                        'active' => $value['active'],
                    ];
                }else{
                    $tmpActivity = [
                        'page' => "",
                        'label' => "...",
                        'active' => false
                    ];
                }
                array_push($listPage, $tmpActivity);
                $i += 1;
            }

            if($activity){
                return response()->json([
                    'status' => 'success',
                    'pagination' => [
                        'current_page' => $activity['current_page'],
                        'last_page' => $activity['last_page'],
                        'previous_page' => $activity['current_page'] != 1 ? $activity['current_page'] - 1 : 1,
                        'next_page' => $activity['current_page'] < $activity['last_page'] ? $activity['current_page'] + 1 : $activity['current_page'],
                        'per_page' => $activity['per_page'],
                        'total' => $activity['total'],
                        'from' => $activity['from'],
                        'to' => $activity['to'],
                        'list_page' => $listPage,
                    ],
                    'data' => $activity['data'],
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
            $fileName = "/assets/illustrations/no-image.png";
            
            $image = $data->image;
            if ($image) {
                $fileName = '/assets/activity/' . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/assets/activity/'), $fileName);
            }

            $activity = new Activity;
            $activity->title = $data->title;
            $activity->id_creator = Auth::user()->id;
            $activity->image = $fileName;
            $activity->description = $data->description;
            $activity->save();

            if($activity){
                return response()->json([
                    'status' => 'success',
                    'data' => $activity,
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

    public function detail($data){
        try{
            $activity = Activity::find($data->id);

            if($activity){
                return response()->json([
                    'status' => 'success',
                    'data' => $activity,
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
            $activity = Activity::find($data->id);
            $fileName = $activity->image;
            
            $image = $data->image;
            if ($image) {
                $fileName = '/assets/activity/' . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/assets/activity/'), $fileName);
            }

            $activity->title = $data->title;
            $activity->image = $fileName;
            $activity->description = $data->description;
            $activity->save();

            if($activity){
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
            $delete = Activity::where('id', $data->id)->delete();

            if($delete){
                return ['status' => 'success', 'message' => 'Success'];
            }else{
                return ['status' => 'fail', 'message' => 'Failed'];
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

}