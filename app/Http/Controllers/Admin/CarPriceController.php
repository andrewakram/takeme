<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarLevel;
use App\Models\CountryCarLevel;
use Illuminate\Http\Request;
use App\Models\Country;
use DB;
use Route;
use Session;


class CarPriceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $results = CountryCarLevel::orderBy('id', 'desc')
            ->join("countries", "countries.id", "country_car_levels.country_id")
            ->join("car_levels", "car_levels.id", "country_car_levels.car_level_id")
            ->select('country_car_levels.id', 'car_levels.name as car_level_name',
                'countries.name_en as country_name_en', 'countries.name as country_name_ar',
                'start_trip_unit', 'waiting_trip_unit', 'distance_trip_unit',
                'rush_start_trip_unit', 'rush_waiting_trip_unit', 'rush_distance_trip_unit',
                'cancellation_trip_unit')
            ->get();
        $countries = Country::get();
        $levels = CarLevel::get();
        return view('cp.carprices.index', [
            'results' => $results,
            'countries' => $countries,
            'levels' => $levels,
        ]);

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'country_id' => 'required',
            'car_level_id' => 'required',
            'start_trip_unit' => 'required',
            'waiting_trip_unit' => 'required',
            'distance_trip_unit' => 'required',
            'rush_start_trip_unit' => 'required',
            'rush_waiting_trip_unit' => 'required',
            'rush_distance_trip_unit' => 'required',
            'cancellation_trip_unit' => 'required',
        ]);
        $add                            = new CountryCarLevel();
        $add->country_id                = $request->country_id;
        $add->car_level_id              = $request->car_level_id;
        $add->start_trip_unit           = $request->start_trip_unit;
        $add->waiting_trip_unit         = $request->waiting_trip_unit;
        $add->distance_trip_unit        = $request->distance_trip_unit;
        $add->rush_start_trip_unit      = $request->rush_start_trip_unit;
        $add->rush_waiting_trip_unit    = $request->rush_waiting_trip_unit;
        $add->rush_distance_trip_unit   = $request->rush_distance_trip_unit;
        $add->cancellation_trip_unit    = $request->cancellation_trip_unit;
        $add->save();
        session()->flash('insert_message', 'تمت العملية بنجاح');
        return back()->with('success', 'Level added successfully');
    }


    public function edit_carprices(Request $request)
    {
        /*$this->validate($request,[
            'name' => 'required',
            'description' => 'required',
        ]);*/
        /*Country::where('id', $request->country_id)
            ->update([
                'name_ar'      => $request->name_ar,
                'name_en'      => $request->name_en,
                'image'      => $request->image
            ]);*/
        $c = CountryCarLevel::where('id', $request->country_car_level_id)->first();
        $c->update($request->all());

        session()->flash('insert_message', 'تمت العملية بنجاح');
        return back()->with('success', 'Record updated successfully');
    }

}
