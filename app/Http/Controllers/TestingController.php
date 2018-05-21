<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\OrderStatus;

class TestingController extends Controller
{
    public function getOrder(Request $request){
        $limit = 10;
        if($request->has('length')){
            $limit = $request->length;
        }

        $limitStart = 0;
        if($request->has('start')){
            $limitStart = $request->start;
        }

        $status = 9;
        if($request->has('status')){
            $status = $request->status;
        }

        if($status == 9){
            $order = Order::skip($limitStart)->take($limit)->get();
        } else{
            $order = Order::where('status', $status)->skip($limitStart)->take($limit)->get();
        }

        return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $order]]);
    }

    public function getOrderStatus(Request $request){
        $orderStatus = OrderStatus::all();

        return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $orderStatus]]);
    }
}
