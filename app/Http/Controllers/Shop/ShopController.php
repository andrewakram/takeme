<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Interfaces\Admin\CategoryRepositoryInterface;
use App\Models\AboutUs;
use App\Models\AppExplanation;
use App\Models\Category;
use App\Models\Country;
use App\Models\Day;
use App\Models\Offer;
use App\Models\Shop;
use App\Models\ShopDay;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function editShopProfile(Request $request){
        $shop=Shop::where('id', Auth::guard('shop')->user()->id)->with('country')->first();
//        dd($shop);
        $categories  = Category::get();

        return view('cp_shop.shops.edit',['categories'=>$categories,'shop'=>$shop]);
    }

    public function updateShopProfile(Request $request){
        $c=Shop::where('id', Auth::guard('shop')->user()->id)->first();
        $c->update($request->except('email','phone'));
        session()->flash('insert_message','تمت العملية بنجاح');
        return back();
//        return back()->with('success','Data updated successfully');
    }

    public function editDailyWork(Request $request){
        //$shop=Shop::where('id', Auth::guard('shop')->user()->id)->first();
        $days = Day::get();
        $shop = ShopDay::where('shop_id', Auth::guard('shop')->user()->id)
            ->get();

//        $categories  = Category::get();

        return view('cp_shop.shops.days',['days'=>$days,'shop'=>$shop]);
    }

    public function updateDailyWork(Request $request){
        //dd($request->all());
        $shop_id = Auth::guard('shop')->user()->id;
        if(isset($request->day_id)){
            ShopDay::where('shop_id',$shop_id)->delete();
            foreach($request->day_id as $day){
                ShopDay::create([
                    'shop_id' => $shop_id,
                    'day_id' => $day,
                    'from' => isset($request->from[$day-1]) ?$request->from[$day-1] : "08:00:00",
                    'to' => isset($request->to[$day-1]) ?$request->to[$day-1] : "11:00:00",
                ]);
            }
        }

        session()->flash('insert_message','تمت العملية بنجاح');
        return redirect(route('shop_home'));
//        return back()->with('success','Data updated successfully');
    }


}
