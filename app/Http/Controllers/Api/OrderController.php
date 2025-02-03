<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Order;
use App\Models\Event;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\OrderTicket;
use App\Service\CreatePaymentUrlService;

class OrderController extends Controller
{
    //create order
    public function create(Request $request)
    {
        //validate the request
        $request->validate([
            "event_id"=> "required|exists:events,id",
            "order_details" => "required|array",
            "order_details.*.sku_id"=> "required|exists::skus,id",
            "quantity"=> "required|integer|min:1",
            "event_date"=> "required",
        ]);

        //$event = Event::find($request->event_id);

        $total = 0;
        foreach ($request->order_details as $orderDetail) {
            $sku =Sku::find($orderDetail['sku_id']);
            $qty = $orderDetail['qty'];
            $total += $sku->price * $qty;
        }

        //create order
        $order = Order::create([
            "user_id" => $request->user()->id,
            "event_id"=> $request->event_id,
            "event_date"=> $request->event_date,
            "quantity"=> $request->quantity,
            "total_price"=> $total,
        ]);

        
        foreach ($request->order_details as $orderDetail) {
            $sku =Sku::find($orderDetail['sku_id']);
            $qty = $orderDetail['qty'];
            
            for($i = 0; $i < $qty; $i++) {
                //ticket by sku and available
                $ticket = Ticket::where('sku_id', $sku->id)
                    ->where('status', 'available')
                    ->first();
                //insert order ticket
                OrderTicket::create([
                    'order_id'=> $order->id,
                    'ticket_id'=> $ticket->id,
                ]);
                //ticket status to sold
                $ticket->update([
                    'status'=> 'booked',
                ]);
            }
        }

        $midtrans = new CreatePaymentUrlService();
        $user = $request->user();
        $order['user'] = $user;
        $order['orderItems'] = $request->order_details;
        $paymentUrl = $midtrans->getPaymentUrl($order);
        $order['paymentUrl'] = $paymentUrl;
        

        //return response
        return response()->json([
            "status"=> "success",
            "message"=> "Order created successfully",
            "data" => $order,
        ],201);
    }   
}
