<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use DB;
use Route;
use Session;


class CityController extends Controller
{
    public function index(){
        $cities = City::orderBy('id','desc')->get();
        return view('admin.cities.index',['cities'=>$cities]);

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'ar_name' => 'required|unique:cities',
            'en_name' => 'required|unique:cities',

        ]);
        $add            = new City();
        $add->ar_name   = $request->ar_name;
        $add->en_name   = $request->en_name;
        $add->save();
        return back()->with('success','City added successfully');
    }



    public function edit_city(Request $request){
        $this->validate($request,[
            'ar_name' => 'required',
            'en_name' => 'required',
        ],[
            'ar_name.required' => 'Arabic text is required',
            'en_name.required' => 'English text is required',
        ]);
        City::where('id', $request->city_id)
            ->update([
                'ar_name'      => $request->ar_name,
                'en_name'      => $request->en_name,
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
