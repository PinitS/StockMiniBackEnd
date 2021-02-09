<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function getProductsFillers(Request $request)
    {
//      new query
        $product_name = $request->input('product_name');
        $mainCategory_id = $request->input('main_category_id');

        if ($mainCategory_id != 'null') {
            $items = Product::whereHas('category', function ($query) use ($mainCategory_id) {
                return $query->where('main_category_id', $mainCategory_id);
            })->get();
        }
        else{
            $items = Product::all();
        }

        if ($product_name != 'null') {
            $items = $items->where('name', '=' , $product_name);
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
}
