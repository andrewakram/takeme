<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gov;
use App\Models\NationalType;
use Illuminate\Http\Request;
use App\Models\Country;
use DB;
use Route;
use Session;


class NationalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $countries = NationalType::orderBy('id','desc')
            ->get();
        return view('cp.nationals.index',[
            'countries'=>$countries,
        ]);

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|unique:national_types,name',

        ]);
        $add            = new NationalType();
        $add->name   = $request->name;

        $add->save();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Country added successfully');
    }



    public function editNational(Request $request){
        $this->validate($request,[
            'name' => 'required|unique:national_types,name',
        ]);

        $c=NationalType::where('id', $request->country_id)->first();
        $c->update($request->all());

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

}
