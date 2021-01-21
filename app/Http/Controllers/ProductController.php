<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductHistory;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;


class ProductController extends Controller
{
    public function getProducts()
    {
        $items = Product::all();
        $dataSet = [];
        foreach ($items as $item) {
            $data = [
                'id' => $item->id,
                'type' => ($item->type == null ? 'No Data' : $item->type->name),
                'name' => $item->name,
                'price' => $item->price,
                'amount' => $item->amount,
                'active' => $item->active,
                'sku' => ($item->productDetail == null ? 'No Data' : $item->productDetail->sku),
                'detail' => ($item->productDetail == null ? 'No Data' : $item->productDetail->detail),
                'img' => $item->productDetail->image,
            ];
            array_push($dataSet, $data);
        }
        return response()->json(['status' => true, 'dataSet' => $dataSet, 'msg' => 'Get Data successfully']);
    }

    public function getProduct($id)
    {
        $item = Product::find($id);
        $dataSet = [
            'id' => $item->id,
            'type' => ($item->type == null ? 'No Data' : $item->type->name),
            'name' => $item->name,
            'price' => $item->price,
            'amount' => $item->amount,
            'active' => $item->active,
            'sku' => ($item->productDetail == null ? 'No Data' : $item->productDetail->sku),
            'detail' => ($item->productDetail == null ? 'No Data' : $item->productDetail->detail),
            'img' => $item->productDetail->image,
        ];
        return response()->json(['status' => true, 'dataSet' => $dataSet, 'msg' => 'Get Data successfully']);
    }

    public function create(Request $request)
    {
        //img_path
        if ($request->has('img_path')) {
            $file = $request->file('img_path');
            $filename = $file->hashName('uploads/');
            $file->move('uploads', $filename);
            $img = Image::make($filename);
            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save();
        } else {
            $filename = null;
        }
        //end img_path
        $item = new Product;
        $item->type_id = $request->input('type_id');
        $item->name = $request->input('name');
        $item->price = $request->input('price');
        $item->amount = $request->input('amount');
        $item->active = $request->input('active');
        if ($item->save()) {
            // product_detail
            $item_detail = new ProductDetail;
            $item_detail->product_id = $item->id;
            $item_detail->sku = $request->input('sku');
            $item_detail->detail = $request->input('detail');
            $item_detail->img_path = $filename;
            $item_detail->recommended_type = $request->input('recommended_type');
            // end_product_detail
            if ($item_detail->save()) {
                // product_history
                $item_history = new ProductHistory;
                $item_history->product_id = $item->id;
                $item_history->amount = $request->input('amount');
                $item_history->type = 1; // 1 == Import 2 == withdraw
                $item_history->detail = 'First Import Product ' . $item->name . ' amount ' . $item->amount;
                if ($item_history->save()) {
                    return response()->json(['status' => true, 'msg' => 'Create successfully']);
                }
                // end_product_history
            }
        }
        return response()->json(['status' => false, 'msg' => 'Create fail']);
    }

    public function update(Request $request)
    {
        $item = Product::find($request->input('id'));
        $item->type_id = $request->input('type_id');
        $item->name = $request->input('name');
        $item->price = $request->input('price');
        $item->active = $request->input('active');
        if ($item->save()) {
            // product_detail
            $item_detail = ProductDetail::find($request->input('id'));
            $item_detail->product_id = $item->id;
            $item_detail->sku = $request->input('sku');
            $item_detail->detail = $request->input('detail');
            // end_product_detail

            //img_path
            if ($request->has('img_path')) {
                File::delete($item_detail->img_path);
                //delete img before add new
                $file = $request->file('img_path');
                $filename = $file->hashName('uploads/');
                $file->move('uploads', $filename);
                $path = $filename;
                $img = Image::make($path);
                $img->resize(150, 150, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save();
            }
            //img_path
            $item_detail->img_path = $filename;
            $item_detail->recommended_type = $request->input('recommended_type');
            if ($item_detail->save()) {
                return response()->json(['status' => true, 'msg' => 'Update successfully']);
            }
        }
        return response()->json(['status' => 'true', 'msg' => 'Update successfully']);
    }

    private function calAmount($id)
    {
        $item = Product::find($id);
        $amount = 0;
        foreach ($item->productHistory as $productHistory) {
            if ($productHistory->type == 1) {
                $amount += $productHistory->amount;
            } else {
                $amount -= $productHistory->amount;
            }
        }
        $item->$amount = $amount;
        $item->save();
    }

    public function delete($id)
    {
        $item = Product::find($id)->delete();
        return response()->json(['status' => 'true', 'msg' => 'Delete successfully']);
    }

}
