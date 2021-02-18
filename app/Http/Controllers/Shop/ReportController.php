<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Shop_detail;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Database\Schema\Blueprint;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return vow_id
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function reports(){

        return view('cp.reports.reportIndex');
    }

    public function makeReport(Request $request){
        Session::put("dateFrom",$request->dateFrom);
        Session::put("dateTo",$request->dateTo);
        Session::put("type",$request->type);
        $newDateFrom = date("Y-m-d", strtotime(Session::get("dateFrom")));
        $newDateTo = date("Y-m-d", strtotime(Session::get("dateTo")));

        if(Session::get("type") == "usersReport"){
            $usercount = User::join("countries","countries.id","users.country_id")
                ->where("is_captin",0)
                ->where("users.created_at",">=",$newDateFrom)
                ->where("users.created_at","<=",$newDateTo)
                ->count();
            $users=User::join("countries","countries.id","users.country_id")
                ->orderBy('id','desc')
                ->where("is_captin",0)
                ->where("users.created_at",">=",$newDateFrom)
                ->where("users.created_at","<=",$newDateTo)
                ->select("users.id","users.name","users.phone","users.email","users.active",
                    "users.suspend","countries.name_ar","countries.name_en","users.image")
                ->get();
            return view('cp.reports.usersReport',[
                'usercount'         =>$usercount,
                'users'             =>$users,
            ]);
        }

        if(Session::get("type") == "driversReport"){
            $usercount=User::join("captin_infos","captin_infos.user_id","users.id")
                ->join("car_levels","car_levels.id","captin_infos.car_level")
                ->join("countries","countries.id","users.country_id")
                ->orderBy('id','desc')
                ->where("captin_infos.created_at",">=",$newDateFrom)
                ->where("captin_infos.created_at","<=",$newDateTo)
                ->where("is_captin",1)
                ->count();
            $users=User::join("captin_infos","captin_infos.user_id","users.id")
                ->join("car_levels","car_levels.id","captin_infos.car_level")
                ->join("countries","countries.id","users.country_id")
                ->orderBy('id','desc')
                ->where("captin_infos.created_at",">=",$newDateFrom)
                ->where("captin_infos.created_at","<=",$newDateTo)
                ->where("is_captin",1)
                ->select("users.id","users.phone","users.email","users.active",
                    "users.suspend","countries.name_ar","countries.name_en","users.is_captin","users.image",
                    "car_color","car_num","car_model","car_levels.name as level")
                ->get();
            return view('cp.reports.driversReport',[
                'usercount'         =>$usercount,
                'users'             =>$users,
            ]);
        }

        if(Session::get("type") == "tripsReport"){
            $usercount= Trip::orderBy('id','desc')
                ->with('user')
                ->with('driver')
                ->where("trips.created_at",">=",$newDateFrom)
                ->where("trips.created_at","<=",$newDateTo)
                ->count();
            $users=Trip::orderBy('id','desc')
                ->with('user')
                ->with('driver')
                ->where("trips.created_at",">=",$newDateFrom)
                ->where("trips.created_at","<=",$newDateTo)
                ->get();
            return view('cp.reports.tripsReport',[
                'usercount'         =>$usercount,
                'users'             =>$users,
            ]);
        }

    }

    public function usersInvoice(){
        $newDateFrom = date("Y-m-d", strtotime(Session::get("dateFrom")));
        $newDateTo = date("Y-m-d", strtotime(Session::get("dateTo")));
        $usercount = User::join("countries","countries.id","users.country_id")
            ->where("is_captin",0)
            ->where("users.created_at",">=",$newDateFrom)
            ->where("users.created_at","<=",$newDateTo)
            ->count();
        $users=User::join("countries","countries.id","users.country_id")
            ->orderBy('id','desc')
            ->where("is_captin",0)
            ->where("users.created_at",">=",$newDateFrom)
            ->where("users.created_at","<=",$newDateTo)
            ->select("users.id","users.name","users.phone","users.email","users.active",
                "users.suspend","countries.name_ar","countries.name_en","users.image")
            ->get();
        return view('cp.reports.usersInvoice',[
            'usercount'         =>$usercount,
            'users'             =>$users,
        ]);
    }

    public function driversInvoice(){
        $newDateFrom = date("Y-m-d", strtotime(Session::get("dateFrom")));
        $newDateTo = date("Y-m-d", strtotime(Session::get("dateTo")));
        $usercount=User::join("captin_infos","captin_infos.user_id","users.id")
            ->join("car_levels","car_levels.id","captin_infos.car_level")
            ->join("countries","countries.id","users.country_id")
            ->orderBy('id','desc')
            ->where("captin_infos.created_at",">=",$newDateFrom)
            ->where("captin_infos.created_at","<=",$newDateTo)
            ->where("is_captin",1)
            ->count();
        $users=User::join("captin_infos","captin_infos.user_id","users.id")
            ->join("car_levels","car_levels.id","captin_infos.car_level")
            ->join("countries","countries.id","users.country_id")
            ->orderBy('id','desc')
            ->where("captin_infos.created_at",">=",$newDateFrom)
            ->where("captin_infos.created_at","<=",$newDateTo)
            ->where("is_captin",1)
            ->select("users.id","users.phone","users.email","users.active",
                "users.suspend","countries.name_ar","countries.name_en","users.is_captin","users.image",
                "car_color","car_num","car_model","car_levels.name as level")
            ->get();
        return view('cp.reports.driversInvoice',[
            'usercount'         =>$usercount,
            'users'             =>$users,
        ]);
    }

    public function tripsInvoice(){
        $newDateFrom = date("Y-m-d", strtotime(Session::get("dateFrom")));
        $newDateTo = date("Y-m-d", strtotime(Session::get("dateTo")));
        $usercount=Trip::orderBy('id','desc')
            ->with('user')
            ->with('driver')
            ->where("trips.created_at",">=",$newDateFrom)
            ->where("trips.created_at","<=",$newDateTo)
            ->count();
        $users=Trip::orderBy('id','desc')
            ->with('user')
            ->with('driver')
            ->where("trips.created_at",">=",$newDateFrom)
            ->where("trips.created_at","<=",$newDateTo)
            ->get();
        return view('cp.reports.tripsInvoice',[
            'usercount'         =>$usercount,
            'users'             =>$users,
        ]);
    }


}
