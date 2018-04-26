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
            $length = 6;
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }

            $orderCode = $request->owner.$randomString.date('Ymd');

            $length = 3;
            $characters = '0123456789';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            $transactionCode = $randomString;

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
            $purchaseOrder->TransactionCode = $transactionCode;
            $purchaseOrder->FinalPrice = $totalPrice + (int) $transactionCode;
            $purchaseOrder->save();

            foreach($productDataWillSave as $productDataWillSave){
                $purchaseOrderDetail = new PurchaseOrderDetail;
                $purchaseOrderDetail->_PurchaseOrder = $orderCode;
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

            return response()->json(['isError' => false, 'message' => 'Order Anda Telah Berhasil Disimpan', 'isResponse' => ['data' => ['orderCode' => $orderCode]]]);
        }
    }

    public function getOrder(Request $request){
        if($request->has('orderCode')){
            $orderData = DB::select('call GET_ORDER_DETAIL(?)',[$request->orderCode]);

            $orderDataOrderCode = null;
            $orderDataUserId = null;
            $orderDataFullname = null;
            $orderDataProvinceId = null;
            $orderDataProvinceName = null;
            $orderDataCityId = null;
            $orderDataCityName = null;
            $orderDataDistrictId = null;
            $orderDataDistrictName = null;
            $orderDataAddress = null;
            $orderDataPostCode = null;
            $orderDataPhone = null;
            $orderDataTransactionCode = null;
            $orderDataPrice = null;
            $orderDataFinalPrice = null;
            $orderDataFinalStatus = null;
            $orderDetail = array();

            foreach($orderData as $orderDetailData){
                $orderDetail[] = array(
                    'productName' => $orderDetailData->ProductName,
                    'productColor' => $orderDetailData->ProductColor,
                    'productSize' => $orderDetailData->ProductSize,
                    'productTotal' => $orderDetailData->ProductTotal,
                    'productPrice' => $orderDetailData->ProductPrice,
                    'productTotalPrice' => $orderDetailData->ProductTotalPrice,
                    'productPhoto' => $orderDetailData->ProductPhoto,
                );

                $orderDataOrderCode = $orderDetailData->OrderCode;
                $orderDataUserId = $orderDetailData->UserId;
                $orderDataFullname = $orderDetailData->Fullname;
                $orderDataProvinceId = $orderDetailData->ProvinceId;
                $orderDataProvinceName = $orderDetailData->ProvinceName;
                $orderDataCityId = $orderDetailData->CityId;
                $orderDataCityName = $orderDetailData->CityName;
                $orderDataDistrictId = $orderDetailData->DistrictId;
                $orderDataDistrictName = $orderDetailData->DistrictName;
                $orderDataAddress = $orderDetailData->Address;
                $orderDataPostCode = $orderDetailData->PostCode;
                $orderDataPhone = $orderDetailData->Phone;
                $orderDataTransactionCode = $orderDetailData->TransactionCode;
                $orderDataPrice = $orderDetailData->Price;
                $orderDataFinalPrice = $orderDetailData->FinalPrice;
                $orderDataStatus = $orderDetailData->Status;
                $orderDataExpiredDt = $orderDetailData->ExpiredDt;
            }

            return response()->json(['isError' => false, 'message' => 'Berhasil Mendapatkan Detail Order',
                    'isResponse' => ['data' => [
                            'orderCode' => $orderDataOrderCode,
                            'userId' => $orderDataUserId,
                            'fullname' => $orderDataFullname,
                            'provinceId' => $orderDataProvinceId,
                            'provinceName' => $orderDataProvinceName,
                            'cityId' => $orderDataCityId,
                            'cityName' => $orderDataCityName,
                            'districtId' => $orderDataDistrictId,
                            'districtName' => $orderDataDistrictName,
                            'address' => $orderDataAddress,
                            'postCode' => $orderDataPostCode,
                            'phone' => $orderDataPhone,
                            'transactionCode' => $orderDataTransactionCode,
                            'price' => $orderDataPrice,
                            'finalPrice' => $orderDataFinalPrice,
                            'status' => $orderDataStatus,
                            'expiredDt' => $orderDataExpiredDt,
                            'products' => $orderDetail
                    ]]]);
        }
    }

    public function getOrderList(Request $request){
        if($request->has('owner')){
            $orderBy = 0;
            if($request->has('orderBy')){
                $orderBy = $request->orderBy;
            }

            $search = "";
            if($request->has('search')){
                $search = $request->search;
            }

            $status = 0;
            if($request->has('status') && $request->status != ''){
                $status = $request->status;
            }

            $orderList = DB::select('call GET_ORDER_LIST(?, ?, ?, ?)',[$request->owner, $orderBy, $search, $status]);

            foreach($orderList as $orderList2){
                $date = explode('-', substr($orderList2->CreatedDt, 0,10));
                $date2 = substr($orderList2->CreatedDt, 11);

                Carbon::setLocale('Asia/Jakarta');
                $date = Carbon::create($date[0], $date[1], $date[2]);
                $orderList2->CreatedDt = $date->formatLocalized('%d %b %Y').' '.$date2;
            }

            return response()->json(['isError' => false, 'isMessage' => 'Pengambilan List Pemesanan Berhasil', 'isResponse' => ['data' => $orderList]]);
        }
    }

    public function updateOrder(Request $request){
        if($request->has('orderCode') && $request->has('status')){
            $order = PurchaseOrder::find($request->orderCode);
            $order->status = $request->status;
            $order->save();

            if($request->status == 1){
                $message = "Berhasil Menandai Pesanan Telah Dibayar";
            } else if($request->status == 2){
                $message = "Berhasil Menandai Pesanan Telah Diproses";
            } else if($request->status == 3){
                $message = "Berhasil Menandai Pesanan Telah Dikirim";
            } else if($request->status == 4){
                $message = "Berhasil Menandai Pesanan Telah Sampai";
            } else{
                $message = '';
            }

            return response()->json(['isError' => false, 'isMessage' => $message, 'isResponse' => ['data' => $order]]);
            dd($order);
        }
    }
}
