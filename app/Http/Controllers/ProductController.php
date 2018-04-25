<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Owner;
use App\Product;
use App\Discount;
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
        if($request->has('name')  && $request->name != '' && $request->name != null){
            $name = $request->name;
        }

        $category = 0;
        if($request->has('category') && $request->category != ''){
            $category = $request->category;
        }

        $gender = 0;
        if($request->has('gender') && $request->gender != ''){
            $gender = $request->gender;
        }

        $color = 0;
        if($request->has('color') && $request->color != ""){
            $color = $request->color;
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

        $productListData = DB::select('call GET_PRODUCT(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',[$owner, $name, $category, $gender, $priceBot, $priceTop, $limit, $limitStart, $orderBy, $color]);

        for($i=0;$i < count($productListData);$i++){
            $productListData[$i]->Photo = url('/').'/app/images/'.$productListData[$i]->Photo;
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

        $category = 0;
        if($request->has('category') && $request->category != ''){
            $category = $request->category;
        }

        $gender = 0;
        if($request->has('gender') && $request->gender != ''){
            $gender = $request->gender;
        }

        $color = 0;
        if($request->has('color')){
            $color = $request->color;
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

        $productListData = DB::select('call GET_PRODUCT(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',[$owner, $name, $category, $gender, $priceBot, $priceTop, $limit, $limitStart, $orderBy, $color]);
        for($i=0;$i < count($productListData);$i++){
            $productListData[$i]->Photo = url('/').'/app/images/'.$productListData[$i]->Photo;
        }

        return response()->json(['isError' => false, 'isMessage' => 'Pengambilan List Produk Berhasil', 'isResponse' => ['data' => ['product' => $productListData, 'total' => count($productListData)]]]);
    }

    public function getDetail(Request $request){
        $productDetailData = DB::select('call GET_PRODUCT_DETAIL(?, ?)',[$request->owner, $request->productId]);

        $colorId = "";
        $colorName = '';
        $sizeId = "";
        $sizeName = '';
        $stock= '';
        $stockTotal = 0;

        foreach($productDetailData as $productDetailData2){
            $productStock = DB::select('call GET_PRODUCT_DETAIL_STOCK(?, ?)',[$request->owner, $productDetailData2->Id]);
            foreach($productStock as $productStock2){
                if($colorId != ''){
                    $colorId = $colorId.',';
                }

                if($colorName != ''){
                    $colorName = $colorName.',';
                }

                if($sizeId != ''){
                    $sizeId = $sizeId.',';
                }

                if($sizeName != ''){
                    $sizeName = $sizeName.',';
                }

                if($stock != ''){
                    $stock = $stock.',';
                }

                $colorId = $colorId.$productStock2->ColorId;
                $colorName = $colorName.$productStock2->ColorName;
                $sizeId = $sizeId.$productStock2->SizeId;
                $sizeName = $sizeName.$productStock2->SizeName;
                $stock = $stock.$productStock2->Total;
                $stockTotal = $stockTotal + $productStock2->Total;
            }

            $date = explode('-', substr($productDetailData2->CreatedDt, 0,10));
            Carbon::setLocale('Asia/Jakarta');
            $date = Carbon::create($date[0], $date[1], $date[2]);
            $productDetailData2->CreatedDt = $date->formatLocalized('%d %b %Y');;
            $productDetailData2->colorId = $colorId;
            $productDetailData2->colorName = $colorName;
            $productDetailData2->sizeId = $sizeId;
            $productDetailData2->sizeName = $sizeName;
            $productDetailData2->stock = $stock;
            $productDetailData2->stockTotal = $stockTotal;

            $productPhotoData = DB::select('call GET_PRODUCT_DETAIL_PHOTO(?, ?)',[$request->owner, $productDetailData2->Id]);

            for($i=0;$i < count($productPhotoData);$i++){
                $productPhotoData[$i]->Photo = url('/').'/app/images/'.$productPhotoData[$i]->Photo;
            }
        }

        return response()->json(['isError' => false, 'isMessage' => 'Pengambilan Detail Produk Berhasil', 'isResponse' => ['data' => ['detail' => $productDetailData, 'photo' => $productPhotoData, 'stock' => $productStock]]]);
    }

    public function getDetailPhoto(Request $request){
        if($request->has('owner') && $request->has('productId')){
            $productPhotoData = DB::select('call GET_PRODUCT_DETAIL_PHOTO(?, ?)',[$request->owner, $request->productId]);

            for($i=0;$i < count($productPhotoData);$i++){
                $productPhotoData[$i]->Photo = url('/').'/app/images/'.$productPhotoData[$i]->Photo;
            }

            return response()->json(['isError' => false, 'isMessage' => 'Pengambilan Detail Produk Berhasil', 'isResponse' => ['data' => ['detail' => $productPhotoData]]]);
        }
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

    public function addProduct(Request $request){
        if($request->has('owner') && $request->has('productName') && $request->has('productCategory') && $request->has('productGender') && $request->has('productPrice')
            && $request->has('productDescription')){

            $uuid = $this->attributes['uuid'] = Uuid::uuid4()->toString();

            $product = new Product;
            $product->_Owner = $request->owner;
            $product->Name = $request->productName;
            $product->Description = $request->productDescription;
            $product->_Gender = $request->productGender;
            $product->_Category = $request->productCategory;
            $product->Price = $request->productPrice;
            $product->Status = 1;
            $product->Uuid = $uuid;
            $product->CreatedDt = Carbon::now()->toDateTimeString();
            $product->CreatedBy = $request->adminId;
            $product->UpdatedDt = Carbon::now()->toDateTimeString();
            $product->UpdatedBy = $request->adminId;
            $product->save();

            $productId = $product->id;

            if($request->has('productIsDiscount') && $request->productIsDiscount != '' && $request->productIsDiscount != null && $request->productIsDiscount != 0){
                $discount = new Discount;
                $discount->_Product = $productId;
                $discount->StartDt = $request->producDiscountStartDt;

                if($request->has('producDiscountEndDt') && $request->producDiscountEndDt != null){
                    $discount->EndDt = $request->producDiscountEndDt;
                } else{
                    $discount->EndDt = null;
                }

                $discount->_DiscountType = $request->productDiscountType;
                $discount->Value = $request->productDiscountVal;
                $discount->CreatedDt = Carbon::now()->toDateTimeString();
                $discount->CreatedBy = $request->adminId;
                $discount->UpdatedDt = Carbon::now()->toDateTimeString();
                $discount->UpdatedBy = $request->adminId;
                $discount->save();
            }

            return response()->json(['isError' => false, 'isMessage' => 'Menambah Produk Berhasil', 'isResponse' => ['data' => $product]]);
        }
    }

    public function addImageProduct(Request $request){
        if($request->has('owner') && $request->has('productId') && $request->has('productUuid') && $request->has('colorId') && $request->has('selected') && $request->has('adminId') && $request->has('file')){
            $owner = Owner::find($request->owner);

            $currDate = Carbon::now()->toDateTimeString();
            $fileName = pathinfo($request->file->getClientOriginalName(), PATHINFO_FILENAME).'-'.$currDate.'.'.$request->file->getClientOriginalExtension();
            $uploadedFile = $request->file('file');
            $uploadedFile = $uploadedFile->storeAs('images/'.$owner->Uuid.'/'.$request->productUuid, $fileName);

            $gallery = new Gallery;
            $gallery->_Product = $request->productId;
            $gallery->_Color = $request->colorId;
            $gallery->Path = $fileName;
            $gallery->Selected = $request->selected;
            $gallery->CreatedDt = $currDate;
            $gallery->CreatedBy = $request->adminId;
            $gallery->UpdatedDt = $currDate;
            $gallery->UpdatedBy = $request->adminId;
            $gallery->save();

            return response()->json(['isError' => false, 'isMessage' => 'Upload Foto Berhasil', 'isResponse' => null]);
        } else{
            return response()->json(['isError' => true, 'isMessage' => 'Upload Foto Gagal', 'isResponse' => null]);
        }
    }
}
