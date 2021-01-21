<?php

namespace App\Http\Controllers;

use App\Models\MainCategory;
use Illuminate\Http\Request;

class MainCategoryController extends Controller
{
    public function getMainCategories()
    {
        $items = MainCategory::all();
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

    public function getMainCategory($id)
    {
        $item = MainCategory::find($id);
        $dataSet = [
            'id' => $item->id,
            'name' => $item->name,
            'detail' => $item->detail,
        ];
        return response()->json(['status' => true, 'dataSet' => $dataSet, 'msg' => 'Get Data successfully']);
    }

    public function create(Request $request)
    {
        $item = new MainCategory;
        $item->name = $request->input('name');
        $item->detail = $request->input('detail');
        $item->save();
        return response()->json(['status' => true, 'msg' => 'Create successfully']);
    }

    public function update(Request $request)
    {
        $item = MainCategory::find($request->input('id'));
        $item->name = $request->input('name');
        $item->detail = $request->input('detail');
        $item->save();
        return response()->json(['status' => 'true', 'msg' => 'Update successfully']);
    }

    public function delete($id)
    {
        $item = MainCategory::find($id)->delete();
        return response()->json(['status' => 'true', 'msg' => 'Delete successfully']);
    }
}
