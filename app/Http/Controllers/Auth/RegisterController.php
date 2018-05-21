<?php

namespace App\Http\Controllers\Auth;

use App\Account;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'owner' => 'required|integer',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    protected function create(Request $request)
    {
        try {
            // $uuid = $this->attributes['uuid'] = Uuid::uuid4()->toString();

            $user =  Account::create([
                '_Owner' => $request->owner,
                'Email' => $request->email,
                'Fullname' => $request->fullname,
                'Password' => Hash::make($request->password),
                'Status' => 1,
                '_Role' => 4,
                'LastLogin' => Carbon::now()->toDateTimeString(),
                'CreatedDt' => Carbon::now()->toDateTimeString(),
            ]);

            return response()->json(['isError' => false, 'message' => 'Registerasi Akun Berhasil', 'isResponse' => ['data' => ['id' =>  $user->id, 'email' => $user->Email, 'name' => $user->Fullname, 'owner' => $user->_Owner, 'role' => $user->_Role, 'phone' => $user->phone ]]]);
        } catch (UnsatisfiedDependencyException $e) {
            return response()->json(['isError' => true, 'message' => 'Terjadi Kesalahan Sistem', 'isResponse' => null]);
        }
    }
}
