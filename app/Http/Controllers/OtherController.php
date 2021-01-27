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

        $dataSet = [
            'DDStore' => Store::pluck( 'name', 'id'),
            'DDMainCategory' => MainCategory::pluck('name', 'id'),
            'DDCategory' => Category::pluck('name', 'id'),
            'DDType' => Type::pluck('name', 'id'),
            'DDRecommended_type' => $dataRecommend,
        ];
        return response()->json(['status' => true, 'dataSet' => $dataSet, 'msg' => 'Get Data successfully']);
    }


    //
}
