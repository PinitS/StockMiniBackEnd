<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function getProducts(Request $request)
    {
        //getAll
//        $items = Product::all();
//        $dataSet = [];
//        foreach ($items as $item) {
//            if ($item->active == 1) {
//                $data = [
//                    'id' => $item->id,
//                    'type' => ($item->type == null ? 'No Data' : $item->type->name),
//                    'name' => $item->name,
//                    'price' => $item->price,
//                    'amount' => $item->amount,
//                    'sku' => ($item->productDetail->sku == null ? 'No Data' : $item->productDetail->sku),
//                    'detail' => ($item->productDetail->detail == null ? 'No Data' : $item->productDetail->detail),
//                    'img' => $item->productDetail->image
//                ];
//                array_push($dataSet, $data);
//            }
//        }
        //GetFilter
        $store_id = $request->input('store_id');
        $mainCategory_id = $request->input('mainCategory_id');
        $category_id = $request->input('category_id');
        $type_id = $request->input('type_id');
        $has_query = false;

        if ($store_id != null) {
            $items = Category::where('store_id', $store_id);
            $has_query_lv1 = true;
        }

        if ($mainCategory_id != null) {
            $items = Category::where('main_category_id', $mainCategory_id);
            $has_query = true;
        }

        if (!$has_query) {
            $items = Category::where('id', '!=', 0);
        }

        if ($category_id != null) {
            $items = $items->where('id', $category_id);
        }

        if ($type_id != null) {
            $items = $items->whereHas('types', function (Builder $query) use ($type_id) {
                $query->where('id', $type_id);
            });
        }
        $items = $items->get();
        $dataSet = [];
        foreach ($items as $item) {
            foreach ($item->types as $type) {
                $data = [
                    'name' => $type->products->id,
                ];
                array_push($dataSet, $data);
            }
        }
        return response()->json(['status' => true, 'dataSet' => $dataSet, 'msg' => 'Get Data successfully']);
    }
}


//$store_id = $request->input('store_id');
//$mainCategory_id = $request->input('mainCategory_id');
//$category_id = $request->input('category_id');
//$type_id = $request->input('type_id');
//
//if ($store_id != null) {
//    if ($mainCategory_id != null) {
//        $categories = Category::where('store_id', $store_id)->where('main_category_id', $mainCategory_id)->get();
//    } else {
//        $categories = Category::where('store_id', $store_id)->get();
//    }
//} else {
//    if ($mainCategory_id != null) {
//        $categories = Category::where('main_category_id', $mainCategory_id)->get();
//    } else {
//        $categories = Category::all();
//    }
//}
//
//if ($category_id != null) {
//    $categories->where('id', $category_id);
//}
//
//if ($type_id != null) {
//    $categories->whereHas('types', function (Builder $query) use ($type_id) {
//        $query->where('id', $type_id);
//    });
//}
//
//$dataSet = $categories;
