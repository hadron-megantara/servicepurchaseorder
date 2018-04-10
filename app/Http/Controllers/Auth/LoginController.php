<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Account;

class LoginController extends Controller
{
    public function login(Request $request){
        if($request->has('email') && $request->has('password')){
            $account = Account::where('email', $request->email)->first();

            if($account){
                if (Hash::check($request->password, $account->Password)) {
                    try {
                        if (!$token = JWTAuth::fromUser($account)) {
                            return response()->json(['isError' => true, 'message' => 'invalid_credentials', 'isResponse' => null]);
                        }
                    } catch (JWTException $e) {
                        return response()->json(['isError' => true, 'message' => 'could_not_create_token', 'isResponse' => null]);
                    }

                    dd($token);
                    return response()->json(['isError' => false, 'message' => 'Registerasi Akun Berhasil', 'isResponse' => ['data' => ['id' =>  $user->id, 'email' => $user->Email, 'name' => $user->Fullname, 'owner' => $user->_Owner, 'role' => $user->_Role, 'phone' => $user->phone ]]]);
                    return response()->json(['isError' => false, 'message' => 'Login Berhasil', 'isResponse' => ['token' => compact('token')]]);
                }
            }
        } else{
            return response()->json(['isError' => true, 'message' => 'Harap isi email dan password', 'isResponse' => null]);
        }
    }
}
