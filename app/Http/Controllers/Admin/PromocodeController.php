<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarLevel;
use App\Models\PromoCode;
use App\Models\Country;
use App\Models\Gov;
use Illuminate\Http\Request;
use App\Models\Rushhour;
use DB;
use Route;
use Session;


class PromocodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $results = PromoCode::orderBy('id','desc')
            ->get();
        foreach ($results as $result){
            $result->countries= Country::orderBy('id','asc')
                ->whereIn('id',explode(',',$result->country_ids))->get();
            $result->car_levels= CarLevel::orderBy('id','asc')
                ->whereIn('id',explode(',',$result->car_level_ids))->get();
        }

        $countries= Country::where('active',1)->get();
        $car_levels= CarLevel::get();

        return view('cp.promocodes.index',[
            'results'=>$results,
            'countries'=>$countries,
            'car_levels'=>$car_levels,
        ]);

    }

    public function store(Request $request)
    {

        /*$this->validate($request,[
            'reason' => 'required',
            'is_captin' => 'required',
        ]);*/
        $country_values ="";
        $car_level_values ="";
        $i=1;
        foreach ($request->country as $key => $value){
            $country_values = $country_values.$value;
            if($i < sizeof($request->country)){
                $country_values = $country_values.',';
                ++$i;
            }
        }
        $i=1;
        foreach ($request->car_level as $key => $value){
            $car_level_values = $car_level_values.$value;
            if($i < sizeof($request->car_level)){
                $car_level_values = $car_level_values.',';
                ++$i;
            }
        }
        //dd($car_level_values);
        $add                = new PromoCode();
        $add->code          = $request->code;
        $add->value         = $request->value;
        $add->type          = $request->type;/*0 = fixed discount , 1 = percentage discount*/
        $add->country_ids   = $country_values;
        $add->car_level_ids = $car_level_values;
        $add->expire_times  = $request->expire_times;
        $add->expire_at     = $request->expire_at;
        $add->en_desc       = $request->en_desc;
        $add->ar_desc       = $request->ar_desc;
        $add->save();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Record added successfully');
    }



//    public function edit_reason(Request $request){
//        $this->validate($request,[
//            'reason' => 'required',
//            'is_captin' => 'required',
//        ]);
//        $c=CancellingReason::where('id', $request->reason_id)->first();
//        $c->update($request->all());
//
//        session()->flash('insert_message','تمت العملية بنجاح');
//        return back()->with('success','Record updated successfully');
//    }

    public function delete_promo(Request $request,$id){

        PromoCode::destroy($id);

        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Record deleted successfully');
    }

}
