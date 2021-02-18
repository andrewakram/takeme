<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gov;
use App\Models\OfferPoint;
use App\Models\ReplacedPoint;
use App\Models\UserReplacedPoint;
use Illuminate\Http\Request;
use App\Models\Country;
use DB;
use Route;
use Session;


class OfferPointController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $countries = OfferPoint::orderBy('id','desc')
            ->get();
        return view('cp.offer_points.index',[
            'countries'=>$countries,
        ]);

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'description' => 'required',
            'code' => 'required|unique:offer_points,code',
            'points' => 'required',
        ]);
        $add            = new OfferPoint();
        $add->description   = $request->description;
        $add->points   = $request->points;
        $add->image     = $request->image;
        $add->code     = $request->code;

        $add->save();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Country added successfully');
    }



    public function editOfferPoint(Request $request){
        $this->validate($request,[
            'description' => 'required',
            'code' => 'required|unique:offer_points,code,'.$request->country_id,
            'points' => 'required',
        ]);

        $c=OfferPoint::where('id', $request->country_id)->first();
        $c->update($request->all());

        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Country updated successfully');
    }

    public function user_offer_points(Request $request){

        $countries=UserReplacedPoint::orderBy('id','desc')
            ->with('user')
            ->with('offer_point')
            ->get();
        return view('cp.users_offer_points.index',[
            'countries' => $countries,
        ]);
    }

    public function editUserOfferPointStatus(Request $request,$id)
    {
        $cat=UserReplacedPoint::where("id",$id)->first();
        if($cat->status == 1){
            UserReplacedPoint::where("id",$id)
                ->update(["status" => 0 ]);
        }else{
            UserReplacedPoint::where("id",$id)
                ->update(["status" => 1 ]);
        }
        session()->flash('insert_message','تمت العملية بنجاح');
        return back();
    }

    public function delegate_offer_points(Request $request){

        $countries=ReplacedPoint::orderBy('id','desc')
            ->with('delegate')
            ->get();
        return view('cp.delegates_offer_points.index',[
            'countries' => $countries,
        ]);
    }

}
