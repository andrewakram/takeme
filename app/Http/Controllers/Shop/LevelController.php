<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarLevel;
use Illuminate\Http\Request;
use App\Models\Country;
use DB;
use Route;
use Session;


class LevelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $countries = CarLevel::orderBy('id','desc')
            ->get();
        return view('cp.levels.index',[
            'countries'=>$countries,
        ]);

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|unique:car_levels,name',
            'image' => 'required',
            'description' => 'required',
        ]);
        $add            = new CarLevel();
        $add->name      = $request->name;
        $add->image     = $request->image;
        $add->description = $request->description;
        $add->save();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Level added successfully');
    }



    public function edit_carlevels(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'description' => 'required',
        ]);
        /*Country::where('id', $request->country_id)
            ->update([
                'name_ar'      => $request->name_ar,
                'name_en'      => $request->name_en,
                'image'      => $request->image
            ]);*/
        $c=CarLevel::where('id', $request->level_id)->first();
        $c->update($request->all());

        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Country updated successfully');
    }

}
