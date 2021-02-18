<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gov;
use Illuminate\Http\Request;
use App\Models\Country;
use DB;
use Route;
use Session;


class GovController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $govs = Gov::orderBy('id','desc')
            ->join("countries","countries.id","govs.country_id")
            ->select("govs.id","govs.name_ar","govs.name_en","countries.id as country_id",
                "countries.name_ar as country_name_ar","countries.name_en as country_name_en")
            ->get();
        $countries = Country::orderBy('id','desc')->get();
        return view('cp.govs.index',[
            'govs'=>$govs,
            'countries'=>$countries,
        ]);

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name_ar' => 'required|unique:govs',
            'name_en' => 'required|unique:govs',
            'country_id' => 'required',
        ]);
        $add            = new Gov();
        $add->name_ar   = $request->name_ar;
        $add->name_en   = $request->name_en;
        $add->country_id   = $request->country_id;
        $add->save();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','City added successfully');
    }



    public function edit_gov(Request $request){
        $this->validate($request,[
            'name_ar' => 'required|',
            'name_en' => 'required|',
            'country_id' => 'required',
        ]);
        Gov::where('id', $request->gov_id)
            ->update($request->except('_token','gov_id'));
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','City updated successfully');
    }

}
