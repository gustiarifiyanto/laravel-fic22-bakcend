<?php

namespace App\Service\Midtrans;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Midtrans\Snap;
use App\Service\Midtrans\Sku;

class CreatePaymenturlService extends Midtrans
{
    protected $order;

    public function __construct()
    {
        parent::__construct();
        //$this->order = $order
    }

    public function getPaymentUrl($order)
    {
        $item_details = new Collection();
        
        foreach ($order->orderItems as $item) {
            $sku = Sku::find($item->sku_id);
            $item_details->push([
                "id"=> $sku->id,
                "price"=> $sku->price,
                "quantity"=> $item->qty,
                "name"=> $sku->name,
            ]);
        }

        $params = [
            'transaction_details' => [
                'order_id' => $order->id,
                'gross_amount' =>$order->total,
            ],
            'item_details' => $item_details,
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
            ]
        ];

        $paymentUrl = Snap::createTransaction($params)->redirect_url;

        return $paymentUrl;
    }
}