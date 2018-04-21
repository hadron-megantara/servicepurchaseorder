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
use Carbon\Carbon;

class ConfigController extends Controller
{
    public function getCategory(Request $request){
        if($request->has('owner')){
            $category = Category::where('_Owner', $request->owner)->orderBy('UpdatedDt', 'DESC')->get();

            return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $category]]);
        }
    }

    public function addCategory(Request $request){
        if($request->has('owner') && $request->has('category')){
            $dt = Carbon::now('Asia/Jakarta');

            $category = new Category;
            $category->_Owner = $request->owner;
            $category->Name = ucfirst($request->category);
            $category->CreatedDt = $dt->toDateTimeString();
            $category->UpdatedDt = $dt->toDateTimeString();
            $category->save();

            return response()->json(['isError' => false, 'message' => 'Sukses Menambah Kategori', 'isResponse' => ['data' => $category]]);
        }
    }

    public function editCategory(Request $request){
        if($request->has('owner') && $request->has('categoryId') && $request->has('category')){
            $dt = Carbon::now('Asia/Jakarta');

            $category = Category::where('Id', $request->categoryId)->where('_Owner', $request->owner)->update(['Name' => ucfirst($request->category), 'UpdatedDt' => $dt->toDateTimeString()]);
            return response()->json(['isError' => false, 'message' => 'Sukses Mengubah Kategori', 'isResponse' => null]);
        }
    }

    public function deleteCategory(Request $request){
        if($request->has('owner') && $request->has('categoryId')){
            $category = Category::where('Id', $request->categoryId)->where('_Owner', $request->owner)->delete();

            return response()->json(['isError' => false, 'message' => 'Sukses Menghapus Kategori', 'isResponse' => null]);
        }
    }

    public function getColor(Request $request){
        if($request->has('owner')){
            $color = Color::where('_Owner', $request->owner)->orderBy('UpdatedDt', 'DESC')->get();

            return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $color]]);
        }
    }

    public function addColor(Request $request){
        if($request->has('owner') && $request->has('color')){
            $dt = Carbon::now('Asia/Jakarta');

            $color = new Color;
            $color->_Owner = $request->owner;
            $color->Name = ucfirst($request->color);
            $color->CreatedDt = $dt->toDateTimeString();
            $color->UpdatedDt = $dt->toDateTimeString();
            $color->save();

            return response()->json(['isError' => false, 'message' => 'Sukses Menambah Warna', 'isResponse' => ['data' => $color]]);
        }
    }

    public function editColor(Request $request){
        if($request->has('owner') && $request->has('colorId') && $request->has('color')){
            $dt = Carbon::now('Asia/Jakarta');
            $color = Color::where('Id', $request->colorId)->where('_Owner', $request->owner)->update(['Name' => ucfirst($request->color), 'UpdatedDt' => $dt->toDateTimeString()]);
            return response()->json(['isError' => false, 'message' => 'Sukses Mengubah Warna', 'isResponse' => null]);
        }
    }

    public function deleteColor(Request $request){
        if($request->has('owner') && $request->has('colorId')){
            $color = Color::where('Id', $request->colorId)->where('_Owner', $request->owner)->delete();

            return response()->json(['isError' => false, 'message' => 'Sukses Menghapus Warna', 'isResponse' => null]);
        }
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
