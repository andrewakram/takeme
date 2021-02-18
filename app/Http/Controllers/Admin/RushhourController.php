<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Gov;
use Illuminate\Http\Request;
use App\Models\Rushhour;
use DB;
use Route;
use Session;


class RushhourController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $results = Rushhour::orderBy('id','desc')
            ->join("countries", "countries.id", "rushhours.country_id")
            ->select('rushhours.id','countries.id as country_id',
                'countries.name_en as country_name_en',
                'countries.name as country_name_ar',
                'from','to')
            ->get();
        $countries = Country::get();
        return view('cp.rushhours.index',[
            'results'=>$results,
            'countries'=>$countries,
        ]);

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'from' => 'required',
            'to' => 'required',
            'country_id' => 'required',
        ]);
        $add                = new Rushhour();
        $add->country_id    = $request->country_id;
        $add->from          = $request->from;
        $add->to            = $request->to;
        $add->save();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Record added successfully');
    }



    public function edit_rushhour(Request $request){
        $this->validate($request,[
            'from' => 'required',
            'to' => 'required',

        ]);
        $c=Rushhour::where('id', $request->rushhour_id)->first();
        $c->update($request->all());

        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Record updated successfully');
    }

    public function delete_rushhour(Request $request,$id){

        Rushhour::destroy($id);

        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Record deleted successfully');
    }

}
