<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Backoffice\MessageService;

class MessageController extends Controller
{
    protected $message;

    public function __construct(MessageService $messageService){
        $this->message = $messageService;
    }

    public function index(Request $request){
        return $this->message->index($request);
    }

    public function readMessage(Request $request){
        return $this->message->readMessage($request);
    }

    public function countMessageUnRead(){
        return $this->message->countMessageUnRead();
    }
}
