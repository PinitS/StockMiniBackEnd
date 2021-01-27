<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getCategories()
    {
        $items = Category::all();
        $dataSet = [];
        foreach ($items as $item) {
            $data = [
                'id' => $item->id,
                'name' => $item->name,
                'detail' => ($item->detail == null ? 'No Data' : $item->detail),
                'mainCategory' => ($item->mainCategory == null ? 'No Data' : $item->mainCategory->name),
                'store' => ($item->store == null ? 'No Data' : $item->store->name),
                'delete_active' => $item->delete_active,
                'main_category_id' => $item->main_category_id,
                'store_id' => $item->store_id
            ];
            array_push($dataSet, $data);
        }
        return response()->json(['status' => true, 'dataSet' => $dataSet, 'msg' => 'Get Data successfully']);
    }

    public function getCategory($id)
    {
        $item = Category::find($id);
        $dataSet = [
            'id' => $item->id,
            'name' => $item->name,
            'detail' => $item->detail,
            'mainCategory' => ($item->mainCategory == null ? 'No Data' : $item->mainCategory->name),
            'store' => ($item->store == null ? 'No Data' : $item->store->name),
        ];
        return response()->json(['status' => true, 'dataSet' => $dataSet, 'msg' => 'Get Data successfully']);
    }

    public function create(Request $request)
    {
        $item = new Category;
        $item->name = $request->input('name');
        $item->detail = $request->input('detail');
        $item->store_id = $request->input('store_id');
        $item->main_category_id = $request->input('main_category_id');
        $item->save();
        return response()->json(['status' => true, 'msg' => 'Create successfully']);
    }

    public function update(Request $request)
    {
        $item = Category::find($request->input('id'));
        $item->name = $request->input('name');
        $item->detail = $request->input('detail');
        $item->store_id = $request->input('store_id');
        $item->main_category_id = $request->input('main_category_id');
        $item->save();
        return response()->json(['status' => 'true', 'msg' => 'Update successfully']);
    }

    public function delete($id)
    {
        $item = Category::find($id);
        if (count($item->types) > 0) {
            return response()->json(['status' => 'true', 'msg' => 'Delete fail']);

        } else {
            $item->delete();
            return response()->json(['status' => 'true', 'msg' => 'Delete successfully']);
        }

    }
}
