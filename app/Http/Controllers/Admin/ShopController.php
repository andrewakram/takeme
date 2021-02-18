<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\CategoryRepositoryInterface;
use App\Models\AboutUs;
use App\Models\AppExplanation;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $type='المتاجر المفعلة';
        $categories  = Category::get();
        $users = Shop::orderBy('id','desc')
            ->with('country')
            ->where('suspend',0)
            ->get();
        return view('cp.shops.index',['users'=>$users,'categories'=>$categories,'type'=>$type]);

    }

    public function createShop(Request $request){
        $categories  = Category::get();
        return view('cp.shops.create',['categories'=>$categories]);
    }

    public function store(Request $request){
//        $this->validate($request,[
//            'ar_title' => 'required|',
//            'en_title' => 'required|',
//            'ar_body' => 'required|',
//            'en_body' => 'required|',
//        ]);
        Shop::create($request->all());
        session()->flash('insert_message','تمت العملية بنجاح');
        return redirect(asset('admin/shops'));
        //return back()->with('success','Data stored successfully');
    }

    public function editShop(Request $request,$shop_id){
        $shop=Shop::where('id', $shop_id)->first();
        $categories  = Category::get();
        return view('cp.shops.edit',['categories'=>$categories,'shop'=>$shop]);
    }

    public function updateShop(Request $request){
        $c=Shop::where('id', $request->model_id)->first();
        $c->update($request->all());
        session()->flash('insert_message','تمت العملية بنجاح');
        return redirect(asset('admin/shops'));
//        return back()->with('success','Data updated successfully');
    }

    public function activeShops(){
        $type='المتاجر المفعلة';
        $categories  = Category::get();
        $users = Shop::orderBy('id','desc')
            ->where('suspend',0)
            ->get();
        return view('cp.shops.index',['users'=>$users,'categories'=>$categories,'type'=>$type]);
    }

    public function inactiveShops(){
        $type='المتاجر الغير مفعلة';
        $categories  = Category::get();
        $users = Shop::orderBy('id','desc')
            ->where('suspend',1)
            ->get();
        return view('cp.shops.index',['users'=>$users,'categories'=>$categories,'type'=>$type]);
    }

    public function editShopStatus(Request $request)
    {

        $cat=Shop::where("id",$request->model_id)->first();
        if($cat->suspend == 1){
            Shop::where("id",$request->model_id)
                ->update(["suspend" => 0 ]);
        }else{
            Shop::where("id",$request->model_id)
                ->update(["suspend" => 1 ]);
        }
        session()->flash('insert_message','تمت العملية بنجاح');
        return back();
    }

    public function editShopVerified(Request $request)
    {

        $cat=Shop::where("id",$request->model_id)->first();
        if($cat->verified == 1){
            Shop::where("id",$request->model_id)
                ->update(["verified" => 0 ]);
        }else{
            Shop::where("id",$request->model_id)
                ->update(["verified" => 1 ]);
        }
        session()->flash('insert_message','تمت العملية بنجاح');
        return back();
    }

    public function delete_shop(Request $request)
    {
        Shop::whereId($request->model_id)->delete();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back();
    }


}
