<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Color;
use App\Gender;
use App\Size;
use App\Province;
use App\City;
use App\District;
use App\DiscountType;

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

    public function getDiscount(Request $request){
        $discountType = DiscountType::all();

        return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $discountType]]);
    }

    public function getProvince(Request $request){
        $province = Province::all();

        return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $province]]);
    }

    public function getCity(Request $request){
        if($request->has('provinceId')){
            $city = City::where('_Province', $request->provinceId)->get();
        } else{
            $city = City::all();
        }

        return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $city]]);
    }

    public function getDistrict(Request $request){
        if($request->has('cityId')){
            $district = District::where('_City', $request->cityId)->get();
        } else{
            $district = District::all();
        }

        return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $district]]);
    }
}
