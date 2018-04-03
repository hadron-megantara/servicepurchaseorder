<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\PurchaseOrder;
use App\PurchaseOrderDetail;

class OrderController extends Controller
{
    public function create(Request $request){
        if($request->has('account') && $request->has('provinceId') && $request->has('cityId') && $request->has('districtId') && $request->has('address') && $request->has('phone') && $request->has('finalPrice')){
            $purchaseOrder = new PurchaseOrder;
            $purchaseOrder->_Account = $request->account;
            $purchaseOrder->_Province = $request->provinceId;
            $purchaseOrder->_City = $request->cityId;
            $purchaseOrder->_District = $request->districtId;
            $purchaseOrder->Address = $request->address;

            if($request->has('postCode')){
                $purchaseOrder->PostCode = $request->postCode;
            }

            $purchaseOrder->Phone = $request->phone;
            $purchaseOrder->Price = $request->finalPrice;
            $purchaseOrder->FinalPrice = $request->finalPrice;
            $purchaseOrder->Status = 0;
            $purchaseOrder->CreatedDt = Carbon::now()->toDateTimeString();

            $purchaseOrder->save();

            return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => 'Purchase Order Success']]);
        }
    }
}
