<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MainCategory;
use App\Models\Store;
use App\Models\Type;
use Illuminate\Http\Request;

class OtherController extends Controller
{
    public function getAllDropDown()
    {
        $dataRecommend = [
            'General', 'Recommended', 'Promotion'
        ];

        $categories = Category::all();
        $dataCategories = [];
        foreach ($categories as $item) {
            $data = [
                'id' => $item->id,
                'name' => $item->name,
                'store_id' => $item->store_id
            ];
            array_push($dataCategories, $data);
        }

        $types = Type::all();
        $dataTypes = [];
        foreach ($types as $item) {
            $data = [
                'id' => $item->id,
                'name' => $item->name,
                'category_id' => $item->category_id
            ];
            array_push($dataTypes, $data);
        }

        $dataSet = [
            'DDStore' => Store::pluck('name', 'id'),
            'DDMainCategory' => MainCategory::pluck('name', 'id'),
            'DDCategory' => Category::pluck('name', 'id'),
            'DDType' => Type::pluck('name', 'id'),
            'DDRecommended_type' => $dataRecommend,
            'DDProductCategory' => $dataCategories,
            'DDProductType' => $dataTypes,
        ];


        return response()->json(['status' => true, 'dataSet' => $dataSet, 'msg' => 'Get Data successfully']);
    }


    //
}
