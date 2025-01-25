<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Events;
use Illuminate\Http\Request;

class EventController extends Controller
{
    //index
    public function index()
    {
        //all event
        $events = Event::all();
        //load event_category and vendor 
        $events->load('eventCategory', 'vendor');
        return response()->json([
            'status'=> 'success',
            'messages'=> 'Events fetched succeccfully',
            'data'=> [],
        ]);
    }
}
