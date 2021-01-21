<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function getStores()
    {
        $items = Store::all();
        $dataSet = [];
        foreach ($items as $item) {
            $data = [
                'id' => $item->id,
                'name' => $item->name,
                'detail' => $item->detail,
            ];
            array_push($dataSet, $data);
        }
        return response()->json(['status' => true, 'dataSet' => $dataSet, 'msg' => 'Get Data successfully']);
    }

    public function getStore($id)
    {
        $item = Store::find($id);
        $dataSet = [
            'id' => $item->id,
            'name' => $item->name,
            'detail' => $item->detail,
        ];
        return response()->json(['status' => true, 'dataSet' => $dataSet, 'msg' => 'Get Data successfully']);
    }

    public function create(Request $request)
    {
        $item = new Store;
        $item->name = $request->input('name');
        $item->detail = $request->input('detail');
        if ($item->save()) {
            return response()->json(['status' => true, 'msg' => 'Create successfully']);
        } else {
            return response()->json(['status' => false, 'msg' => 'Create fail']);
        }
    }

    public function update(Request $request)
    {
        $item = Store::find($request->input('id'));
        $item->name = $request->input('name');
        $item->detail = $request->input('detail');
        $item->save();
        return response()->json(['status' => 'true', 'msg' => 'Update successfully']);
    }

    public function delete($id)
    {
        $item = Store::find($id)->delete();
        return response()->json(['status' => 'true', 'msg' => 'Delete successfully']);
    }
}
