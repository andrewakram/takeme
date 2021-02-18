<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Gov;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\Country;
use DB;
use Illuminate\Support\Facades\Auth;
use Route;
use Session;


class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $countries = Menu::orderBy('id','desc')
            ->where('shop_id',Auth::guard('shop')->user()->id)
            ->get();
        return view('cp_shop.menues.index',[
            'countries'=>$countries,
        ]);

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
        ]);
        $add            = new Menu();
        $add->name   = $request->name;
        $add->shop_id   = Auth::guard('shop')->user()->id;
        $add->save();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Country added successfully');
    }



    public function editMenu(Request $request){
        $this->validate($request,[
            'name' => 'required|',
        ]);

        Menu::where('id', $request->model_id)->update($request->except('_token','model_id'));
        //$c->update($request->all());

        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Country updated successfully');
    }

    public function editCountryStatus(Request $request,$id)
    {
        $cat=Country::where("id",$id)->first();
        if($cat->active == 1){
            Country::where("id",$id)
                ->update(["active" => 0 ]);
        }else{
            Country::where("id",$id)
                ->update(["active" => 1 ]);
        }
        session()->flash('insert_message','تمت العملية بنجاح');
        return back();
    }

    public function deleteMenu(Request $request)
    {
        Menu::whereId($request->model_id)->delete();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back();
    }

}
