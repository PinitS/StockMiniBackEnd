<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductHistory;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function getProductsFillers(Request $request)
    {
//      new query
        $store_id = $request->input('store_id');
        $mainCategory_id = $request->input('mainCategory_id');
        $category_id = $request->input('category_id');
        $type_id = $request->input('type_id');

        if ($mainCategory_id != 'null') {
            $items = Product::whereHas('category', function ($query) use ($mainCategory_id) {
                return $query->where('main_category_id', $mainCategory_id);
            })->get();
        } else {
            $items = Product::all();
        }

        if ($store_id != 'null') {
            $items = $items->where('store_id', $store_id);
        }

        if ($category_id != 'null') {
            $items = $items->where('category_id', $category_id);
        }
        if ($type_id != 'null') {
            $items = $items->where('type_id', $type_id);
        }

        $dataSet = [];

        foreach ($items as $item) {
            if ($item->active == 1) {
                $data = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'type' => ($item->type == null ? 'No Data' : $item->type->name),
                    'price' => $item->price,
                    'amount' => $item->amount,
                    'active' => $item->active,
                    'sku' => ($item->productDetail == null ? 'No Data' : ($item->productDetail->sku == null ? 'No Data' : $item->productDetail->detail)),
                    'detail' => ($item->productDetail == null ? 'No Data' : ($item->productDetail->detail == null ? 'No Data' : $item->productDetail->detail)),
                    'img' => $item->productDetail->image,
                    'delete_active' => $item->delete_active,
                ];
                array_push($dataSet, $data);
            }
        }
        return response()->json(['status' => true, 'dataSet' => $dataSet, 'msg' => 'Get Data successfully']);
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
        $item = Product::find($request->input('id'));
        // product_history
        $item_history = new ProductHistory;
        $item_history->user_id = $request->input('user_id');
        $item_history->product_id = $item->id;
        $item_history->amount = $request->input('amount');
        $item_history->type = $request->input('mode');
        $item_history->order_id = 0;
        $item_history->detail = ($request->input('mode') == 1 ? 'Import Product' : 'Withdraw Product') . $item->name . ' amount ' . $request->input('amount');
        if ($item_history->save()) {
            $this->calAmount($item->id);
            return response()->json(['status' => true, 'msg' => 'Add amount successfully']);
        }
        return response()->json(['status' => false, 'msg' => 'Add amount fail']);
    }
}
