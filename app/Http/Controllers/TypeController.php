<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function getTypes()
    {
        $items = Type::all();
        $dataSet = [];
        foreach ($items as $item) {
            $data = [
                'id' => $item->id,
                'category' => ($item->category == null ? 'No Data' : $item->category->name),
                'name' => $item->name,
                'detail' => $item->detail,
            ];
            array_push($dataSet, $data);
        }
        return response()->json(['status' => true, 'dataSet' => $dataSet, 'msg' => 'Get Data successfully']);
    }

    public function getType($id)
    {
        $item = Type::find($id);
        $dataSet = [
            'id' => $item->id,
            'category' => ($item->category == null ? 'No Data' : $item->category->name),
            'name' => $item->name,
            'detail' => $item->detail,
        ];
        return response()->json(['status' => true, 'dataSet' => $dataSet, 'msg' => 'Get Data successfully']);
    }

    public function create(Request $request)
    {
        $item = new Type;
        $item->name = $request->input('name');
        $item->detail = $request->input('detail');
        $item->category_id = $request->input('category_id');
        $item->save();
        return response()->json(['status' => true, 'msg' => 'Create successfully']);
    }

    public function update(Request $request)
    {
        $item = Type::find($request->input('id'));
        $item->name = $request->input('name');
        $item->detail = $request->input('detail');
        $item->category_id = $request->input('category_id');
        $item->save();
        return response()->json(['status' => 'true', 'msg' => 'Update successfully']);
    }

    public function delete($id)
    {
        $item = Type::find($id)->delete();
        return response()->json(['status' => 'true', 'msg' => 'Delete successfully']);
    }
    //
}
