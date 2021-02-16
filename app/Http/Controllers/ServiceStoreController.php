<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class ServiceStoreController extends Controller
{
    public function getOrderStatusOne()
    {
        $items = Order::where('status', '=', 1)->get();
        $dataSet = [];
        foreach ($items as $item) {
            $dataOrder = [];
            foreach ($item->orderDetails as $detail) {
                $data = [
                    'order_id' => $item->id,
                    'orderDetails_id' => $detail->id,
                    'status' => $detail->status,
                    'name' => $detail->product->name,
                    'product_id' => $detail->product->id,
                    'amount' => $detail->amount,
                    'price' => $detail->product->price,
                ];
                array_push($dataOrder, $data);
            }
            array_push($dataSet,  $dataOrder);
        }
        return response()->json(['status' => true, 'dataSet' => $dataSet, 'msg' => 'Get Data Cart successfully']);
    }
}
