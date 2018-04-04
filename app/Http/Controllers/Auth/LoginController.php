<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use APP\Account;

class LoginController extends Controller
{
    public function login(Request $request){
        if($request->has('email') && $request->has('password')){
            $credentials = $request->only('email', 'password');

            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['isError' => true, 'message' => 'invalid_credentials', 'isResponse' => null]);
                }
            } catch (JWTException $e) {
                return response()->json(['isError' => true, 'message' => 'could_not_create_token', 'isResponse' => null]);
            }

            $user = JWTAuth::authenticate($token);
            dd($user);
            return response()->json(['isError' => false, 'message' => 'Registerasi Akun Berhasil', 'isResponse' => ['data' => ['id' =>  $user->id, 'email' => $user->Email, 'name' => $user->Fullname, 'owner' => $user->_Owner, 'role' => $user->_Role, 'phone' => $user->phone ]]]);
            return response()->json(['isError' => false, 'message' => 'Login Berhasil', 'isResponse' => ['token' => compact('token')]]);
        } else{
            return response()->json(['isError' => true, 'message' => 'Harap isi email dan password', 'isResponse' => null]);
        }
    }
}
