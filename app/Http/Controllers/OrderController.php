<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\ProductHistory;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private function calAmount($id)
    {
        $item = Product::find($id);
        $amount = 0;
        foreach ($item->productHistory as $productHistory) {
            if ($productHistory->status == 1) {
                if ($productHistory->type == 1) {
                    $amount += $productHistory->amount;
                } else {
                    $amount -= $productHistory->amount;
                }
            }
        }
        $item->amount = $amount;
        $item->save();
    }

    public function create(Request $request)
    {
        $user_id = $request->input('user_id');
        $product_id = $request->input('product_id');
        $amount = $request->input('amount');
        $item = Order::where('user_id', '=', $user_id)->where('status', '=', 0)->first();
        $product = Product::find($product_id);
        if ($item == null) {
            $item = new Order();
            $item->user_id = $user_id;
            $item->status = 0;
            $item->save();
        }
        $item_detail = new OrderDetails();
        $item_detail->order_id = $item->id;
        $item_detail->product_id = $product_id;
        $item_detail->amount = $amount;
        $item_detail->save();

        $item_history = new ProductHistory();
        $item_history->user_id = $user_id;
        $item_history->product_id = $product_id;
        $item_history->amount = $amount;
        $item_history->type = 2; // 1 == Import 2 == withdraw
        $item_history->detail = 'Sell Product ' . $product->name . ' amount ' . $amount;

        if ($item_history->save()) {
            $this->calAmount($product_id);
        }
        return response()->json(['status' => true, 'msg' => 'Get Data successfully']);
    }

    public function getOrder(Request $request)
    {
        $user_id = $request->input('user_id');
        $items = Order::where('user_id', '=', $user_id)->where('status', '=', 0)->first();
        $dataSet = [];
        $dataSumUse = [];
        $sum = 0;
        $sum_all_amount = 0;
        $sum_all_price = 0;
        foreach ($items->orderDetails as $item) {
            $sum = $item->amount * $item->product->price;
            $sum_all_amount += $item->amount;
            $sum_all_price += $sum;
            $data = [
                'id' => $items->id,
                'status' => $items->status,
                'product' => $item->product->name,
                'amount' => $item->amount,
                'price' => $item->product->price,
                'sum_price' => $sum,
            ];

            array_push($dataSet, $data);
        }
        $dataSum = [
            'sum_all_amount' => $sum_all_amount,
            'sum_all_price' => $sum_all_price,
        ];
        array_push($dataSumUse, $dataSum);
        return response()->json(['status' => true, 'DataSet' => $dataSet, 'DataSum' => $dataSumUse, 'msg' => 'Get Data successfully']);
    }
}
