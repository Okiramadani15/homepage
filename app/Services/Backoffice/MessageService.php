<?php

namespace App\Services\Backoffice;

use App\Models\Homepage\Message;

class MessageService {
    public function index($data){
        try{
            $limit = 15;
            if($data->limit){
                $limit = $data->limit;
            }
            
            $message = new Message;
            $message = $message->orderBy('created_at', 'asc');
            $message = $message->where('subject', 'like', '%'.$data->search.'%');
            $message = $message->paginate($data->limit)->onEachSide(1)->toArray();
            
            $listPage = [];
            $i = 1;

            foreach($message['links'] as $value){
                $explodeUrl = explode("page=", $value['url']);

                if($i == 1){
                    $tmpMessage = [
                        'page' => $message['current_page'] != 1 ? $message['current_page'] - 1 : 1,
                        'label' => "Previous",
                        'active' => $value['active'],
                    ];
                }elseif($i == $message['last_page'] + 2){
                    $tmpMessage = [
                        'page' => $message['current_page'] < $message['last_page'] ? $message['current_page'] + 1 : $message['current_page'],
                        'label' => "Next",
                        'active' => $value['active'],
                    ];
                }elseif($value['url'] != null){
                    $tmpMessage = [
                        'page' => $explodeUrl[1],
                        'label' => $value['label'],
                        'active' => $value['active'],
                    ];
                }else{
                    $tmpMessage = [
                        'page' => "",
                        'label' => "...",
                        'active' => false
                    ];
                }
                array_push($listPage, $tmpMessage);
                $i += 1;
            }

            if($message){
                return response()->json([
                    'status' => 'success',
                    'pagination' => [
                        'current_page' => $message['current_page'],
                        'last_page' => $message['last_page'],
                        'previous_page' => $message['current_page'] != 1 ? $message['current_page'] - 1 : 1,
                        'next_page' => $message['current_page'] < $message['last_page'] ? $message['current_page'] + 1 : $message['current_page'],
                        'per_page' => $message['per_page'],
                        'total' => $message['total'],
                        'from' => $message['from'],
                        'to' => $message['to'],
                        'list_page' => $listPage,
                    ],
                    'data' => $message['data'],
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

    public function readMessage($data){
        try{
            $message = Message::where('id', $data->id)->first();
            $message->is_read = true;
            $message->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Success'
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function countMessageUnRead(){
        try{
            $count = Message::where('is_read', false)->count();
            $data = ['total' => $count];
            return response()->json([
                'status' => 'success',
                'data' => $data,
                'message' => 'Success'
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }
}