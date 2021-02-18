<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\City;
use DB;
use Route;
use Session;


class CityController extends Controller
{
    public function index(){
        $countries = Country::whereActive(1)->get();
        $cities = City::orderBy('id','desc')->get();
        return view('cp.cities.index',[
            'cities'=>$cities,
            'countries'=>$countries,
        ]);

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|unique:cities,name',

        ]);
        $country = Country::whereId($request->country_id)->first();
        $add            = new City();
        $add->name   = $request->name;
        $add->country_id   = $request->country_id;
        $add->code   = $country->code;
        $add->save();
        return back()->with('success','City added successfully');
    }



    public function edit_city(Request $request){
        $this->validate($request,[
            'name' => 'required',

        ]);
        $country = Country::whereId($request->country_id)->first();
        City::where('id', $request->city_id)
            ->update([
                'name'      => $request->name,
                'country_id'      => $request->country_id,
                'code'      => $country->code,
            ]);
        return back()->with('success','City updated successfully');
    }
    
    public function editCityStatus(Request $request,$id)
    {
        $cat=City::where("id",$id)->first();
        if($cat->active == 1){
            City::where("id",$id)
                ->update(["active" => 0 ]);
        }else{
            City::where("id",$id)
                ->update(["active" => 1 ]);
        }
        return back();
    }

}
