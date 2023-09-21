<?php

namespace App\Http\Controllers\Backoffice\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Backoffice\Setting\GenderService;

class GenderController extends Controller
{
    protected $gender;

    public function __construct(GenderService $genderService){
        $this->gender = $genderService;
    }

    public function index(){
        return $this->gender->index();
    }
}
