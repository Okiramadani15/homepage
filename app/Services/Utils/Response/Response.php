<?php

namespace App\Services\Utils\Response;

class Response{
    public function responseSuccess($data){
        return response()->json([
            'status' => 'success'
        ]);
    }
}