<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Color;
use App\Gender;
use App\Size;

class ConfigController extends Controller
{
    public function getCategory(Request $request){
        $category = Category::all();

        return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $category]]);
    }

    public function getColor(Request $request){
        $color = Color::all();

        return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $color]]);
    }

    public function getGender(Request $request){
        $gender = Gender::all();

        return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $gender]]);
    }

    public function getSize(Request $request){
        $size = Size::all();

        return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $size]]);
    }
}
