<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductHistory;
use Illuminate\Http\Request;

class ProductHistoryController extends Controller
{
    public function getAll($id)
    {
        $items = ProductHistory::where('product_id' , $id)->get();
        $dataSet = [];
        foreach ($items as $item) {
            $data = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'amount' => $item->amount,
                'type' => ($item->type == 1 ?'Import' : 'Withdraw'),
                'detail' => ($item->detail == null ?' No Data' : $item->detail),
                'user_id' => $item->user_id,
                'status' => $item->status,
                'created_at' => $item->created_at,

            ];
            array_push($dataSet, $data);
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
    public function changeAmountHistory(Request $request){

        $item = ProductHistory::find($request->input('id'));
        $item->amount = $request->input('amount');
        $item->detail = "****Edit " . $item->product->name . " amount ".$item->amount . "****";
        if($item->save()){
            $this->calAmount($item->product_id);
            return response()->json(['status' => true, 'msg' => 'Change Amount successfully']);
        }
        else{
            return response()->json(['status' => false,'msg' => 'Change Amount fail']);
        }
    }

    public function changeStatus(Request $request){

        $item = ProductHistory::find($request->input('id'));
        $item->status = $request->input('status');
        if($item->save()){
            $this->calAmount($item->product_id);
            return response()->json(['status' => true, 'msg' => 'Change Status successfully']);
        }
        else{
            return response()->json(['status' => false, 'msg' => 'Change Status fail']);
        }
    }

}
