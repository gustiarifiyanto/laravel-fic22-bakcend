<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Events;
use Illuminate\Http\Request;
use App\Models\EventCategory;

class EventController extends Controller
{
    //index
    public function index($request)
    {
        //event by category_id
        $events = Events::where('event_category_id', $request->category_id)->get();
        //if category_id all
        if ($request->category_id == 'all') {
            $events = Events::all();
        }
        //all event
        $events = Event::all();
        //load event_category and vendor 
        $events->load('eventCategory', 'vendor');
        return response()->json([
            'status'=> 'success',
            'messages'=> 'Events fetched succeccfully',
            'data'=> $events,
        ]);
    }

    //get all event categories
    public function categories()
    {
        //get all event categories
        $categories = EventCateogory::all();
        return response()->json([
            'status'=> 'success',
            'message'=> 'Event categories fetched successfully',
            'data'=> $categories,
        ]);
    }

    //detail event and sku by event_id
    public function detail(Request $request)
    {
        //event by event_id
        $event = Event::find($request->event_id);
        //load event_category and vendor
        $event->load('eventCategory', 'vendor');
        $skus = $event->skus;
        $event['skus'] = $skus;
        return response()->json([
            'status'=> 'success',
            'message'=> 'Event fetched successfully',
            'data'=> $event,
        ]);
    }
}
