<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CalendarRequest;
use App\Services\Backoffice\EducationalCalendarService;

class EducationalCalendarController extends Controller
{
    protected $calendar;

    public function __construct(EducationalCalendarService $calendarService){
        $this->calendar = $calendarService;
    }

    public function index(Request $request){
        return $this->calendar->index($request);
    }

    public function create(CalendarRequest $request){
        return $this->calendar->create($request);
    }

    public function detail(Request $request){
        return $this->calendar->detail($request);
    }

    public function update(CalendarRequest $request){
        return $this->calendar->update($request);
    }

    public function delete(Request $request){
        return $this->calendar->delete($request);
    }
}
