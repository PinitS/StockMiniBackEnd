<?php

namespace App\Http\Controllers;

use App\Models\OrderDetails;
use App\Models\Product;
use Illuminate\Http\Request;

class StoreOrderController extends Controller
{
    public function getOrderDetails(Request $request)
    {
        $store_id = $request->input('store_id');
        $items = OrderDetails::whereHas('product', function ($query) use ($store_id) {
            return $query->where('store_id', $store_id);
        });
        $items = $items->where('status', 0)->orderBy('product_id', 'asc')->orderBy('id', 'asc')->get();
        $dataSet = [];
        foreach ($items as $item) {
            $data = [
                'id' => $item->id,
                'order_id' => $item->order_id,
                'amount' => $item->amount,
                'name' => $item->product->name,
                'status' => $item->status
            ];
            array_push($dataSet, $data);
        }
        return response()->json(['status' => true, 'dataSet' => $dataSet, 'msg' => 'StoreOrderDetails successfully']);
    }

    public function updateStatus(Request $request)
    {
        $id = $request->input('id');
        $item = OrderDetails::whereIn('id', $id)->update(['status' => 1]);

        return response()->json(['status' => true, 'dataSet' => $item, 'msg' => 'StoreOrderDetails successfully']);
    }
    //
}
