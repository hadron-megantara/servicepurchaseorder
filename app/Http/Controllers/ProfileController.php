<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Account;
use App\Address;

class ProfileController extends Controller
{
    public function getProfile(Request $request){
        $user = $request->get('user');

        return response()->json(['isError' => false, 'message' => 'Berhasil Mendapatkan Data Profile', 'isResponse' => ['data' => $user]]);
    }

    public function addAddress(Request $request){
        if($request->has('userId')){
            Carbon::setLocale('Asia/Jakarta');

            $address = new Address;
            $address->_Account = $request->userId;
            $address->Name = $request->name;
            $address->Address = $request->address;
            $address->_Province = $request->province;
            $address->_City = $request->city;
            $address->_District = $request->district;
            $address->CreatedDt = Carbon::now()->toDateTimeString();
            $address->UpdatedDt = Carbon::now()->toDateTimeString();
            $address->save();

            eturn response()->json(['isError' => false, 'message' => 'Berhasil Menambah Alamat', 'isResponse' => ['data' => $address]]);
        }
    }
}
