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

    public function getAddress(Request $request){
        $user = $request->get('user');

        $address = DB::select('call GET_USER_ADDRESS(?)',[$user->Id]);

        $total = count($address);

        return response()->json(['isError' => false, 'isMessage' => 'Berhasil Mendapatkan Daftar Alamat', 'isResponse' => ['data' => $address, 'total' => $total]]);
    }

    public function addAddress(Request $request){
        $user = $request->get('user');

        Carbon::setLocale('Asia/Jakarta');

        $address = new Address;
        $address->_Account = $user->Id;
        $address->RecipientName = ucfirst($request->recipientName);
        $address->RecipientPhone = $request->recipientPhone;
        $address->Name = ucfirst($request->name);
        $address->Address = $request->address;
        $address->_Province = $request->province;
        $address->_City = $request->city;
        $address->_District = $request->district;
        $address->CreatedDt = Carbon::now()->toDateTimeString();
        $address->UpdatedDt = Carbon::now()->toDateTimeString();
        $address->save();

        return response()->json(['isError' => false, 'isMessage' => 'Berhasil Menambah Alamat', 'isResponse' => ['data' => $address]]);
    }

    public function editAddress(Request $request){
        $user = $request->get('user');

        Carbon::setLocale('Asia/Jakarta');

        $address = Address::where('Id', $request->id)->where('_Account', $user->Id)->first();
        $address->RecipientName = ucfirst($request->recipientName);
        $address->RecipientPhone = $request->recipientPhone;
        $address->Name = ucfirst($request->name);
        $address->Address = $request->address;
        $address->_Province = $request->province;
        $address->_City = $request->city;
        $address->_District = $request->district;
        $address->CreatedDt = Carbon::now()->toDateTimeString();
        $address->UpdatedDt = Carbon::now()->toDateTimeString();
        $address->save();

        return response()->json(['isError' => false, 'isMessage' => 'Berhasil Mengubah Alamat', 'isResponse' => ['data' => $address]]);
    }
}
