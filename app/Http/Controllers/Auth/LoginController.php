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
    private $guard;

    public function __construct()
    {

        // $this->guard = \Auth::guard('api');
    }

    public function login(Request $request){
        if($request->has('email') && $request->has('password') && $request->has('owner')){
            $account = Account::where('Email', $request->email)->where('_Owner', $request->owner)->first();

            if($account){
                if (Hash::check($request->password, $account->Password)) {
                    try {
                        if (!$token = JWTAuth::fromUser($account)) {
                            return response()->json(['isError' => true, 'message' => 'invalid_credentials', 'isResponse' => null]);
                        }
                    } catch (JWTException $e) {
                        return response()->json(['isError' => true, 'message' => 'could_not_create_token', 'isResponse' => null]);
                    }

                    JWTAuth::setToken($token);
                    $payload = JWTAuth::getPayload();
                    $expirationTime = $payload['exp'];

                    return response()->json(['isError' => false, 'message' => 'Login Berhasil', 'isResponse' => ['token' => ['value' => $token, 'expiration' => $expirationTime], 'data' => ['id' =>  $account->Id, 'email' => $account->Email, 'name' => $account->Fullname, 'owner' => $account->_Owner, 'role' => $account->_Role, 'phone' => $account->Phone, 'status' => $account->Status ]]]);
                }
            }
        } else{
            return response()->json(['isError' => true, 'message' => 'Harap isi email dan password', 'isResponse' => null]);
        }
    }
}
