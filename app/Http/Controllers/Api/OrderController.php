<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Order;

class OrderController extends Controller
{
    //create order
    public function create(Request $request)
    {
        //validate the request
        $request->validate([
            "event-id"=> "required|exists:events,id",
            "sku_id"=> "required|exists:skus,id",
            "quantity"=> "required|integer|min:1",
            "customer_name"=> "required|string",
            "customer_email"=> "required|email",
            "customer_phone"=> "required|string",
            "customer_address"=> "required|string",
        ]);

        //create order
        $order = Order::create([
            "event_id"=> $request->event_id,
            "sku_id"=> $request->sku_id,
            "quanityt"=> $request->quantity,
            "customer_name"=> $request->customer_name,
            "customer_email"=> $request->customer_email,
            "customer_phone"=> $request->customer_phone,
            "customer_address"=> $request->customer_address,
        ]);

        //return response
        return response()->json([
            "status"=> "success",
            "message"=> "Order created successfully",
            "data" => $order,
        ],201);
    }   
}
