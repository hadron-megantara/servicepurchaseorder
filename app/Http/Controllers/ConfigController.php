<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Owner;
use App\Category;
use App\Color;
use App\Gender;
use App\Size;
use App\Province;
use App\City;
use App\District;
use App\DiscountType;
use App\Bank;
use App\BankAccount;

class ConfigController extends Controller
{
    public function getCategory(Request $request){
        if($request->has('owner')){
            $category = Category::where('_Owner', $request->owner)->orderBy('UpdatedDt', 'DESC')->get();

            return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $category]]);
        }
    }

    public function addCategory(Request $request){
        if($request->has('owner') && $request->has('category')){
            $dt = Carbon::now('Asia/Jakarta');

            $category = new Category;
            $category->_Owner = $request->owner;
            $category->Name = ucfirst($request->category);
            $category->CreatedDt = $dt->toDateTimeString();
            $category->UpdatedDt = $dt->toDateTimeString();
            $category->save();

            return response()->json(['isError' => false, 'message' => 'Sukses Menambah Kategori', 'isResponse' => ['data' => $category]]);
        }
    }

    public function editCategory(Request $request){
        if($request->has('owner') && $request->has('categoryId') && $request->has('category')){
            $dt = Carbon::now('Asia/Jakarta');

            $category = Category::where('Id', $request->categoryId)->where('_Owner', $request->owner)->update(['Name' => ucfirst($request->category), 'UpdatedDt' => $dt->toDateTimeString()]);
            return response()->json(['isError' => false, 'message' => 'Sukses Mengubah Kategori', 'isResponse' => null]);
        }
    }

    public function deleteCategory(Request $request){
        if($request->has('owner') && $request->has('categoryId')){
            $category = Category::where('Id', $request->categoryId)->where('_Owner', $request->owner)->delete();

            return response()->json(['isError' => false, 'message' => 'Sukses Menghapus Kategori', 'isResponse' => null]);
        }
    }

    public function getColor(Request $request){
        if($request->has('owner')){
            $color = Color::where('_Owner', $request->owner)->orderBy('UpdatedDt', 'DESC')->get();

            return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $color]]);
        }
    }

    public function addColor(Request $request){
        if($request->has('owner') && $request->has('color')){
            $dt = Carbon::now('Asia/Jakarta');

            $color = new Color;
            $color->_Owner = $request->owner;
            $color->Name = ucfirst($request->color);
            $color->CreatedDt = $dt->toDateTimeString();
            $color->UpdatedDt = $dt->toDateTimeString();
            $color->save();

            return response()->json(['isError' => false, 'message' => 'Sukses Menambah Warna', 'isResponse' => ['data' => $color]]);
        }
    }

    public function editColor(Request $request){
        if($request->has('owner') && $request->has('colorId') && $request->has('color')){
            $dt = Carbon::now('Asia/Jakarta');
            $color = Color::where('Id', $request->colorId)->where('_Owner', $request->owner)->update(['Name' => ucfirst($request->color), 'UpdatedDt' => $dt->toDateTimeString()]);
            return response()->json(['isError' => false, 'message' => 'Sukses Mengubah Warna', 'isResponse' => null]);
        }
    }

    public function deleteColor(Request $request){
        if($request->has('owner') && $request->has('colorId')){
            $color = Color::where('Id', $request->colorId)->where('_Owner', $request->owner)->delete();

            return response()->json(['isError' => false, 'message' => 'Sukses Menghapus Warna', 'isResponse' => null]);
        }
    }

    public function getGender(Request $request){
        $gender = Gender::all();

        return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $gender]]);
    }

    public function getSize(Request $request){
        $size = Size::all();

        return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $size]]);
    }

    public function getDiscount(Request $request){
        $discountType = DiscountType::all();

        return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $discountType]]);
    }

    public function getProvince(Request $request){
        $province = Province::all();

        return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $province]]);
    }

    public function getCity(Request $request){
        if($request->has('provinceId')){
            $city = City::where('_Province', $request->provinceId)->get();
        } else{
            $city = City::all();
        }

        return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $city]]);
    }

    public function getDistrict(Request $request){
        if($request->has('cityId')){
            $district = District::where('_City', $request->cityId)->get();
        } else{
            $district = District::all();
        }

        return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $district]]);
    }

    public function getBankList(Request $request){
        $bankList = Bank::all();

        return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $bankList]]);
    }

    public function getBankAccount(Request $request){
        if($request->has('owner')){
            $bankAccount = DB::select('call GET_BANK_ACCOUNT(?)',[$request->owner]);

            $bankAccountResponse = array();
            if(count($bankAccount) > 0){
                foreach($bankAccount as $bankAccount){
                    $bankAccount->BankPath = url('/').'/img/logo/bank/'.$bankAccount->BankPath;
                    $bankAccountResponse[] = $bankAccount;
                }
            }

            return response()->json(['isError' => false, 'message' => '', 'isResponse' => ['data' => $bankAccountResponse]]);
        }
    }

    public function addBankAccount(Request $request){
        if($request->has('owner') && $request->has('adminId') && $request->has('accountName') && $request->has('accountNumber') && $request->has('bankId') && $request->has('accountBranch')){
            $dt = Carbon::now('Asia/Jakarta');

            $bankAccount = new BankAccount;
            $bankAccount->_Owner = $request->owner;
            $bankAccount->_Bank = $request->bankId;
            $bankAccount->AccountName = $request->accountName;
            $bankAccount->AccountNumber = $request->accountNumber;
            $bankAccount->Branch = ucfirst($request->accountBranch);
            $bankAccount->CreatedDt = $dt->toDateTimeString();
            $bankAccount->CreatedBy = $request->adminId;
            $bankAccount->UpdatedDt = $dt->toDateTimeString();
            $bankAccount->UpdatedBy = $request->adminId;
            $bankAccount->save();

            return response()->json(['isError' => false, 'message' => 'Sukses Menambah Akun Bank', 'isResponse' => ['data' => $bankAccount]]);
        }
    }

    public function editBankAccount(Request $request){
        if($request->has('owner') && $request->has('categoryId') && $request->has('category')){
            $dt = Carbon::now('Asia/Jakarta');

            $category = Category::where('Id', $request->categoryId)->where('_Owner', $request->owner)->update(['Name' => ucfirst($request->category), 'UpdatedDt' => $dt->toDateTimeString()]);
            return response()->json(['isError' => false, 'message' => 'Sukses Mengubah Kategori', 'isResponse' => null]);
        }
    }

    public function deleteBankAccount(Request $request){
        if($request->has('owner') && $request->has('categoryId')){
            $category = Category::where('Id', $request->categoryId)->where('_Owner', $request->owner)->delete();

            return response()->json(['isError' => false, 'message' => 'Sukses Menghapus Kategori', 'isResponse' => null]);
        }
    }

    public function getOwner(Request $request){
        if($request->has('owner')){
            $owner = Owner::find($request->owner);

            $owner->Logo = url('/').'/app/images/'.$owner->Uuid.'/logo/'.$owner->Logo;

            return response()->json(['isError' => false, 'message' => 'Berhasil Mendapatkan Detail Owner', 'isResponse' => ['data' => $owner]]);
        }
    }

    public function editOwner(Request $request){
        if($request->has('owner')){
            $owner = Owner::where('Id', $request->owner)->firstOrFail();
            $owner->Name = $request->name;
            $owner->Phone = $request->phone;
            $owner->Address = $request->address;

            if($request->has('file')){
                $currDate = Carbon::now()->toDateTimeString();
                $fileName = pathinfo($request->file->getClientOriginalName(), PATHINFO_FILENAME).'-'.$currDate.'.'.$request->file->getClientOriginalExtension();
                $uploadedFile = $request->file('file');
                $uploadedFile = $uploadedFile->storeAs('images/'.$owner->Uuid.'/logo', $fileName);

                $owner->Logo = $fileName;
            }

            $owner->save();

            $owner = Owner::find($request->owner);

            return response()->json(['isError' => false, 'message' => 'Berhasil Mengubah Info', 'isResponse' => ['data' => $owner]]);
        }
    }
}
