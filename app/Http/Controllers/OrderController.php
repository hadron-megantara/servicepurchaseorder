<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\PurchaseOrder;
use App\PurchaseOrderDetail;

class OrderController extends Controller
{
    public function create(Request $request){
        if($request->has('owner') && $request->has('account') && $request->has('fullname') && $request->has('province') && $request->has('city') && $request->has('district') && $request->has('address') && $request->has('phone') && $request->has('productList')){
            $length = 4;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }

            $orderCode = $request->owner.$randomString.date('Ymd');

            $purchaseOrder = new PurchaseOrder;
            $purchaseOrder->OrderCode = $orderCode;
            $purchaseOrder->_Account = $request->account;
            $purchaseOrder->Fullname = $request->fullname;
            $purchaseOrder->_Province = $request->province;
            $purchaseOrder->_City = $request->city;
            $purchaseOrder->_District = $request->district;
            $purchaseOrder->Address = $request->address;

            if($request->has('postCode') && $request->postCode != ''){
                $purchaseOrder->PostCode = $request->postCode;
            }

            $purchaseOrder->Phone = $request->phone;
            $purchaseOrder->Status = 0;
            $purchaseOrder->CreatedDt = Carbon::now()->toDateTimeString();

            $productList = $request->productList;
            $productList = explode(',', $productList);
            $productIdList = "";
            $productArray = array();
            foreach($productList as $productList2){
                $productListData = explode('-', $productList2);
                $productArray[$productListData[0]] = $productListData[0];
            }

            foreach($productArray as $productArray){
                if($productIdList != ''){
                    $productIdList = $productIdList.',';
                }

                $productIdList = $productIdList.$productArray;
            }

            $productDataWillSave = array();
            $totalPrice = 0;
            $productListResponse = DB::select('call GET_PRODUCT_DETAIL_List(?, ?)',[$request->owner, $productIdList]);
// Log::debug($productListResponse);
            foreach($productListResponse as $productListResponse){
                foreach($productList as $productList2){
                    $productListData = explode('-', $productList2);
                    if($productListResponse->Id == $productListData[0] && $productListResponse->ColorId == $productListData[1]
                        && $productListResponse->SizeId == $productListData[2]){
                        $productDataWillSave[] = array(
                            'id' => $productListData[0],
                            'color' => $productListData[1],
                            'size' => $productListData[2],
                            'total' => $productListData[3],
                            'price' => $productListResponse->newPrice,
                            'totalPrice' => $productListData[3] * $productListResponse->newPrice,
                            'isDiscount' => $productListResponse->DiscountType,
                            'discountId' => $productListResponse->DiscountId,
                            'discount' => $productListResponse->Discount
                        );
                        $totalPrice = $totalPrice + $productListData[3] * $productListResponse->newPrice;
                    }
                }
            }

            $purchaseOrder->Price = $totalPrice;
            $purchaseOrder->FinalPrice = $totalPrice;
            $purchaseOrder->save();

            foreach($productDataWillSave as $productDataWillSave){
                $purchaseOrderDetail = new PurchaseOrderDetail;
                $purchaseOrderDetail->_PurchaseOrder = $purchaseOrder->id;
                $purchaseOrderDetail->_Product = $productDataWillSave['id'];
                $purchaseOrderDetail->_Color = $productDataWillSave['color'];
                $purchaseOrderDetail->_Size = $productDataWillSave['size'];

                if($productDataWillSave['isDiscount'] != null && $productDataWillSave['isDiscount'] != ''){
                    $purchaseOrderDetail->isDiscount = 1;
                    $purchaseOrderDetail->_Discount = $productDataWillSave['discountId'];
                }

                $purchaseOrderDetail->Total = $productDataWillSave['total'];
                $purchaseOrderDetail->Price = $productDataWillSave['price'];
                $purchaseOrderDetail->TotalPrice = $productDataWillSave['totalPrice'];
                $purchaseOrderDetail->CreatedDt = Carbon::now()->toDateTimeString();
                $purchaseOrderDetail->save();
            }

            return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => 'Purchase Order Success']]);
        }
    }
}
