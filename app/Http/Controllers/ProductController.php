<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use App\Category;
use App\Color;
use App\Gender;
use App\Size;
use App\Stock;
use App\Gallery;

class ProductController extends Controller
{
    public function getList(Request $request){
        $owner = 0;
        if($request->has('owner') && $request->owner != ''){
            $owner = $request->owner;
        }

        $name = '';
        if($request->has('name')  && $request->name != ''){
            $name = $request->name;
        }

        $description = '';
        if($request->has('description') && $request->description != ''){
            $description = $request->description;
        }

        $category = 0;
        if($request->has('category') && $request->category != ''){
            $category = $request->category;
        }

        $gender = 0;
        if($request->has('gender') && $request->gender != ''){
            $gender = $request->gender;
        }

        $priceBot = 0;
        $priceTop = 0;
        if($request->has('priceBot') && $request->priceBot != '' && $request->has('priceTop') && $request->priceTop != ''){
            $priceBot = $request->priceBot;
            $priceTop = $request->priceTop;
        }

        $limit = env('PRODUCT_LIST_LIMIT', 15);
        $limitStart = 0;
        if($request->has('limit') && $request->limit != ''){
            $limit = $request->limit;
        }

        if($request->has('limitStart') && $request->limitStart != ''){
            $limitStart = $request->limitStart;
        }

        $orderBy = 0;
        if($request->has('orderBy')){
            $orderBy = $request->orderBy;
        }

        $productListData = DB::select('call GET_PRODUCT(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',[$owner, $name, $description, $category, $gender, $priceBot, $priceTop, $limit, $limitStart, $orderBy]);

        for($i=0;$i < count($productListData);$i++){
            $productListData[$i]->Photo = 'app/images/'.$productListData[$i]->Photo;
        }

        return response()->json(['isError' => false, 'isMessage' => 'Pengambilan List Produk Berhasil', 'isResponse' => ['data' => ['product' => $productListData, 'total' => count($productListData)]]]);
    }

    public function getListEventNew(Request $request){
        $owner = 0;
        if($request->has('owner') && $request->owner != ''){
            $owner = $request->owner;
        }

        $name = '';
        if($request->has('name')  && $request->name != ''){
            $name = $request->name;
        }

        $description = '';
        if($request->has('description') && $request->description != ''){
            $description = $request->description;
        }

        $category = 0;
        if($request->has('category') && $request->category != ''){
            $category = $request->category;
        }

        $gender = 0;
        if($request->has('gender') && $request->gender != ''){
            $gender = $request->gender;
        }

        $priceBot = 0;
        $priceTop = 0;
        if($request->has('priceBot') && $request->priceBot != '' && $request->has('priceTop') && $request->priceTop != ''){
            $priceBot = $request->priceBot;
            $priceTop = $request->priceTop;
        }

        $limit = env('PRODUCT_LIST_LIMIT', 10);
        $limitStart = 0;
        if($request->has('limit') && $request->limit != ''){
            $limit = $request->limit;
        }

        if($request->has('limitStart') && $request->limitStart != ''){
            $limitStart = $request->limitStart;
        }

        $orderBy = 0;
        if($request->has('orderBy')){
            $orderBy = $request->orderBy;
        }

        $productListData = DB::select('call GET_PRODUCT(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',[$owner, $name, $description, $category, $gender, $priceBot, $priceTop, $limit, $limitStart, $orderBy]);
        for($i=0;$i < count($productListData);$i++){
            $productListData[$i]->Photo = 'app/images/'.$productListData[$i]->Photo;
        }

        return response()->json(['isError' => false, 'isMessage' => 'Pengambilan List Produk Berhasil', 'isResponse' => ['data' => ['product' => $productListData, 'total' => count($productListData)]]]);
    }

    public function getDetail(Request $request){
        $productDetailData = DB::select('call GET_PRODUCT_DETAIL(?, ?)',[$request->owner, $request->productId]);

        return response()->json(['isError' => false, 'isMessage' => 'Pengambilan Detail Produk Berhasil', 'isResponse' => ['data' => ['detail' => $productDetailData]]]);
    }

    public function getDetailPhoto(Request $request){
        $productPhotoData = DB::select('call GET_PRODUCT_DETAIL_PHOTO(?, ?)',[$request->owner, $request->productId]);

        return response()->json(['isError' => false, 'isMessage' => 'Pengambilan Detail Produk Berhasil', 'isResponse' => ['data' => ['detail' => $productPhotoData]]]);
    }

    public function getDetailStock(Request $request){
        if($request->has('productId') && $request->has('owner')){
            $productStock = DB::select('call GET_PRODUCT_DETAIL_STOCK(?, ?)',[$request->owner, $request->productId]);

            return response()->json(['isError' => false, 'isMessage' => 'Pengambilan Detail Produk Berhasil', 'isResponse' => ['data' => $productStock]]);
        }
    }

    public function getDetailList(Request $request){
        if($request->has('productIdList') && $request->has('owner')){
            $productList = DB::select('call GET_PRODUCT_DETAIL_List(?, ?)',[$request->owner, $request->productIdList]);

            return response()->json(['isError' => false, 'isMessage' => '', 'isResponse' => ['data' => $productList]]);
        }
    }

    public function getPhotoByProducColor(Request $request){
        $photo = DB::select('call GET_PRODUCT_DETAIL_PHOTO_COLOR(?, ?, ?)',[$request->owner, $request->productId, $request->color]);

        return response()->json(['isError' => false, 'isMessage' => '', 'isResponse' => ['data' => $photo]]);
    }
}
