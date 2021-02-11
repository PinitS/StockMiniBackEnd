<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\ProductHistory;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getOrder(Request $request)
    {
        $user_id = $request->input('user_id');
        $items = Order::where('user_id', '=', $user_id)->where('status', '=', 0)->first();
        $dataSet = [];
        $sum = 0;
        $sum_all_amount = 0;
        $sum_all_price = 0;
        if ($items == null) {
            return response()->json(['status' => true, '$items' => $items, 'msg' => 'Get Data Cart successfully']);
        }
        foreach ($items->orderDetails as $item) {
            $sum = $item->amount * $item->product->price;
            $sum_all_amount += $item->amount;
            $sum_all_price += $sum;
            $data = [
                'orderDetails_id' => $item->id,
                'max_amount' => $item->product->amount,
                'order_id' => $items->id,
                'status' => $items->status,
                'product_id' => $item->product->id,
                'name' => $item->product->name,
                'amount' => $item->amount,
                'price' => $item->product->price,
                'sum_price' => $sum,
            ];

            array_push($dataSet, $data);
        }
        $order = [
            'id' => $items->id,
            'status' => $items->status,
            'user_id' => $items->user_id
        ];
        $dataSum = (object)['sum_all_amount' => $sum_all_amount, 'sum_all_price' => $sum_all_price,];
        return response()->json(['status' => true, 'order' => $order, 'dataSet' => $dataSet, 'dataSum' => $dataSum, 'msg' => 'Get Data Cart successfully']);
    }

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
        $item = Order::where('user_id', '=', $user_id)->where('status', '=', 0)->first();
        $product = Product::find($product_id);
        if ($item == null) {
            $item = new Order();
            $item->user_id = $user_id;
            $item->status = 0;
            $item->save();
        }
        $check_duplicate = OrderDetails::where('product_id', '=', $product_id)->where('order_id', '=', $item->id)->first();
        if ($check_duplicate != null) {
            $item_New_detail = OrderDetails::find($check_duplicate->id);
            $item_New_detail->amount = $check_duplicate->amount + 1;
            $item_New_detail->save();
            $item_history = ProductHistory::where('product_id', '=', $product_id)->where('user_id', '=', $user_id)->get()->last();
            $item_history->amount = $item_history->amount + 1;
            $item_history->detail = 'Sell Product ' . $product->name . ' amount ' . ($item_history->amount);
            $item_history->order_id = $item->id;
            if ($item_history->save()) {
                $this->calAmount($product_id);
            }
        } else {
            $item_detail = new OrderDetails();
            $item_detail->order_id = $item->id;
            $item_detail->product_id = $product_id;
            $item_detail->amount = 1;
            $item_detail->save();
            $item_history = new ProductHistory();
            $item_history->user_id = $user_id;
            $item_history->product_id = $product_id;
            $item_history->amount = 1;
            $item_history->type = 2; // 1 == Import 2 == withdraw
            $item_history->detail = 'Sell Product ' . $product->name . ' amount ' . 1;
            $item_history->order_id = $item->id;
            if ($item_history->save()) {
                $this->calAmount($product_id);
            }
        }
        return response()->json(['status' => true, 'msg' => 'Add Data to Cart successfully']);
    }

    public function delete($order_id, $product_id)
    {
        $item = OrderDetails::where('product_id', '=', $product_id)->where('order_id', '=', $order_id)->first();
        $item->delete();
        $item_history = ProductHistory::where('product_id', '=', $product_id)->where('order_id', '=', $order_id)->first();
        $item_history->delete();
        $this->calAmount($product_id);
        return response()->json(['status' => true, 'msg' => 'Delete Data to Cart successfully']);
    }

    public function changeAmount(Request $request)
    {
        $orderDetails_id = $request->input('orderDetails_id');
        $product_id = $request->input('product_id');
        $order_id = $request->input('order_id');

        $item = OrderDetails::find($orderDetails_id);
        $item->amount = $request->input('amount');
        $item->save();

        $item_history = ProductHistory::where('product_id', '=', $product_id)->where('order_id', '=', $order_id)->first();
        $item_history->amount = $request->input('amount');
        $item_history->detail = 'Sell Product ' . $item->product->name . ' amount ' . $request->input('amount');
        if ($item_history->save()) {
            $this->calAmount($product_id);
        }

        return response()->json(['status' => true, 'msg' => 'Change Data in Cart successfully']);
    }

    public function changeStatus(Request $request)
    {
        $order_id = $request->input('id');
        $item = Order::find($order_id);
        $item->status = 1;
        $item->save();
        return response()->json(['status' => true, 'dataSet' => $item, 'msg' => 'Change Data in Cart successfully']);
    }

    public function getOrderStatusOne(Request $request)
    {
        $user_id = $request->input('user_id');
        $items = Order::where('user_id', '=', $user_id)->where('status', '=', 1)->get();
        $dataSet = [];

        foreach ($items as $item) {
            $dataOrder = [];
            $sum_all_amount = 0;
            $sum_all_price = 0;
            foreach ($item->orderDetails as $detail) {
                $sum = $detail->amount * $detail->product->price;
                $sum_all_amount += $detail->amount;
                $sum_all_price += $sum;
                $data = [
                    'orderDetails_id' => $detail->id,
                    'name' => $detail->product->name,
                    'product_id' => $detail->product->id,
                    'amount' => $detail->amount,
                    'price' => $detail->product->price,
                    'sum_price' => $sum,
                ];
                $dataSum = (object)['sum_all_amount' => $sum_all_amount, 'sum_all_price' => $sum_all_price,];
//                array_push($dataOrder, (object)[
//                    'order' => $data,
//                    'sum' => $dataSum
//                ]);
                array_push($dataOrder, $data);

            }
            array_push($dataSet, (object)['data' => $dataOrder , 'sum' => $dataSum , 'order_id' => $item->id]);
        }
        return response()->json(['status' => true, 'dataSet' => $dataSet, 'msg' => 'Get Data Cart successfully']);
    }

}
