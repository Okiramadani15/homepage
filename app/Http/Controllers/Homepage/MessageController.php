<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\MessageRequest;
use App\Models\Homepage\Message;

class MessageController extends Controller
{
    public function index(Request $request){
        return view('contact.index');
    }

    public function create(Request $request){
        $create = new Message;
        $create->name = $request->name;
        $create->email = $request->email;
        $create->subject = $request->subject;
        $create->message = $request->message;
        $create->save();

        if(!$create){
            return redirect('/hubungi-kami')
            ->with('status', 'error')
            ->with('title', 'Pesan gagal dikirim ke admin Al-Hasyimiyah')
            ->with('message', 'Periksa kembali pesan anda, Terima Kasih');    
        }else{
            return redirect('/hubungi-kami')
            ->with('status', 'success')
            ->with('title', 'Pesan berhasil dikirim ke admin Al-Hasyimiyah')
            ->with('message', 'Admin akan membalas pesan anda melalui email, silahkan cek email anda. Terima Kasih. :)');
        }
    }
}
