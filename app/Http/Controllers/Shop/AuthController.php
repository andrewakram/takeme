<?php

namespace App\Http\Controllers\Shop;

use App\Models\Category;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login_view()
    {
        if(Auth::guard('shop')->user())
        {
            return redirect('/shop/home');
        }

        return view('cp_shop.login.login');
    }

    public function login(Request $request)
    {
        if(Auth::guard('shop')->attempt(['email' => $request->email, 'password' => $request->password], false))
        {

            return redirect(route('shop_home'));
        }else{
            return back()->with('error', 'Invalid Credentials');
        }
    }

    public function logout()
    {
        Auth::guard('shop')->logout();
        return redirect('shop/login');
    }
    /////////////////////////////////////////////////////////////

//    public function editShopProfile(Request $request){
//        $shop=Shop::where('id', Auth::guard('shop')->user()->id)->first();
////        dd($shop);
//        $categories  = Category::get();
//
//        return view('cp_shop.shops.edit',['categories'=>$categories,'shop'=>$shop]);
//    }
//
//    public function updateShopProfile(Request $request){
//        $c=Shop::where('id', Auth::guard('shop')->user()->id)->first();
//        $c->update($request->all());
//        session()->flash('insert_message','تمت العملية بنجاح');
//        return back();
////        return back()->with('success','Data updated successfully');
//    }

}
