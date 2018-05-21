<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;

class CustomerController extends Controller
{
    public function getCustomer(Request $request){
        if($request->has('owner')){
            $customer = Account::where('_Owner', env('OWNER_ID', 1))->where('_Role', 4)->orderBy('UpdatedDt', 'desc')->get();

            return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $customer]]);
        }
    }
}
