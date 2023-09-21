<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserServices;

class UserController extends Controller
{
    protected $user;

    public function __construct(UserServices $userService){
        $this->user = $userService;
    }

    public function register(Request $request){
        return $this->user->userCreated($request);
    }

    public function profile(){
        return $this->user->getProfile();
    }

    public function login(Request $request){
        return $this->user->userLogin($request);
    }

    public function logout(Request $request){
        return $this->user->userLogout($request);
    }

    public function user(Request $request){
        return $this->user->getAllUser($request);
    }

    public function detail(Request $request){
        return $this->user->detailUser($request);
    }

    public function update(Request $request){
        return $this->user->updateUser($request);
    }

    public function delete(Request $request){
        return $this->user->deleteUser($request);
    }

}
