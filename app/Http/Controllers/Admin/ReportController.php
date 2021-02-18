<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Delegate;
use App\Models\Trip;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Shop;
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
                ->where("users.created_at",">=",$newDateFrom)
                ->where("users.created_at","<=",$newDateTo)
                ->count();
            $users=User::join("countries","countries.id","users.country_id")
                ->orderBy('id','desc')
                ->where("users.created_at",">=",$newDateFrom)
                ->where("users.created_at","<=",$newDateTo)
                ->select("users.id","users.name","users.phone","users.email","users.active",
                    "users.suspend","countries.name","users.image")
                ->get();
            return view('cp.reports.usersReport',[
                'usercount'         =>$usercount,
                'users'             =>$users,
            ]);
        }

        if(Session::get("type") == "driversReport"){
            $usercount=Driver::join("car_levels","car_levels.id","drivers.car_level")
                ->join("countries","countries.id","drivers.country_id")
                ->orderBy('id','desc')
                ->where("drivers.created_at",">=",$newDateFrom)
                ->where("drivers.created_at","<=",$newDateTo)
                ->count();
            $users=Driver::join("car_levels","car_levels.id","drivers.car_level")
                ->join("countries","countries.id","drivers.country_id")
                ->orderBy('id','desc')
                ->where("drivers.created_at",">=",$newDateFrom)
                ->where("drivers.created_at","<=",$newDateTo)
                ->select("drivers.id","drivers.phone","drivers.email","drivers.active",
                    "f_name","l_name",
                    "drivers.suspend","countries.name","drivers.image",
                    "car_color","car_num","car_levels.name as level")
                ->get();
            return view('cp.reports.driversReport',[
                'usercount'         =>$usercount,
                'users'             =>$users,
            ]);
        }

        if(Session::get("type") == "delegatesReport"){
            $usercount=Delegate::join("car_levels","car_levels.id","delegates.car_level")
                ->join("countries","countries.id","delegates.country_id")
                ->orderBy('id','desc')
                ->where("delegates.created_at",">=",$newDateFrom)
                ->where("delegates.created_at","<=",$newDateTo)
                ->count();
            $users=Delegate::join("car_levels","car_levels.id","delegates.car_level")
                ->join("countries","countries.id","delegates.country_id")
                ->orderBy('id','desc')
                ->where("delegates.created_at",">=",$newDateFrom)
                ->where("delegates.created_at","<=",$newDateTo)
                ->select("delegates.id","delegates.phone","delegates.email","delegates.active",
                    "f_name","l_name",
                    "delegates.suspend","countries.name as country_name","delegates.image",
                    "car_color","car_num","car_levels.name as level")
                ->get();
            return view('cp.reports.delegatesReport',[
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

        if(Session::get("type") == "shopsReport"){
            $usercount=Shop::join("categories","categories.id","shops.category_id")
                ->join("countries","countries.id","shops.country_id")
                ->orderBy('id','desc')
                ->where("shops.created_at",">=",$newDateFrom)
                ->where("shops.created_at","<=",$newDateTo)
                ->count();
            $users=Shop::join("categories","categories.id","shops.category_id")
                ->join("countries","countries.id","shops.country_id")
                ->orderBy('id','desc')
                ->where("shops.created_at",">=",$newDateFrom)
                ->where("shops.created_at","<=",$newDateTo)
                ->select("shops.id","shops.name","shops.phone","shops.email","shops.active",
                    "shops.suspend","countries.name","shops.image",
                    "categories.name as cat_name")
                ->get();
            return view('cp.reports.shopsReport',[
                'usercount'         =>$usercount,
                'users'             =>$users,
            ]);
        }

        if(Session::get("type") == "ordersShopsReport"){
            $usercount=Order::with('user')
                ->where('department_id',2)
                ->where("created_at",">=",$newDateFrom)
                ->where("created_at","<=",$newDateTo)
                ->count();
            $users=Order::with('user')
                ->where('department_id',2)
                ->with('delegate')
                ->with(["order_products" => function ($query) {
                    $query->with(["order_product_variations" => function ($query) {
                        $query->with('order_product_variation_options');
                    }]);
                }])
                ->with("order_images")
                ->where("created_at",">=",$newDateFrom)
                ->where("created_at","<=",$newDateTo)
                ->get();
            foreach($users as $order){
                $order->order_status = OrderStatus::where('order_id',$order->id)->first();
            }
            return view('cp.reports.ordersShopsReport',[
                'usercount'         =>$usercount,
                'users'             =>$users,
            ]);
        }

        if(Session::get("type") == "ordersNormalReport"){
            $usercount=Order::with('user')
                ->where('department_id',3)
                ->where("created_at",">=",$newDateFrom)
                ->where("created_at","<=",$newDateTo)
                ->count();
            $users=Order::with('user')
                ->where('department_id',3)
                ->with('delegate')
                ->with(["order_products" => function ($query) {
                    $query->with(["order_product_variations" => function ($query) {
                        $query->with('order_product_variation_options');
                    }]);
                }])
                ->with("order_images")
                ->where("created_at",">=",$newDateFrom)
                ->where("created_at","<=",$newDateTo)
                ->get();
            foreach($users as $order){
                $order->order_status = OrderStatus::where('order_id',$order->id)->first();
            }
            return view('cp.reports.ordersNormalReport',[
                'usercount'         =>$usercount,
                'users'             =>$users,
            ]);
        }

    }

    public function usersInvoice(){
        $newDateFrom = date("Y-m-d", strtotime(Session::get("dateFrom")));
        $newDateTo = date("Y-m-d", strtotime(Session::get("dateTo")));
        $usercount = User::join("countries","countries.id","users.country_id")
            ->where("users.created_at",">=",$newDateFrom)
            ->where("users.created_at","<=",$newDateTo)
            ->count();
        $users=User::join("countries","countries.id","users.country_id")
            ->orderBy('id','desc')
            ->where("users.created_at",">=",$newDateFrom)
            ->where("users.created_at","<=",$newDateTo)
            ->select("users.id","users.name","users.phone","users.email","users.active",
                "users.suspend","countries.name","users.image")
            ->get();
        return view('cp.reports.usersInvoice',[
            'usercount'         =>$usercount,
            'users'             =>$users,
        ]);
    }

    public function delegatesInvoice(){
        $newDateFrom = date("Y-m-d", strtotime(Session::get("dateFrom")));
        $newDateTo = date("Y-m-d", strtotime(Session::get("dateTo")));
        $usercount=Delegate::join("car_levels","car_levels.id","delegates.car_level")
            ->join("countries","countries.id","delegates.country_id")
            ->orderBy('id','desc')
            ->where("delegates.created_at",">=",$newDateFrom)
            ->where("delegates.created_at","<=",$newDateTo)
            ->count();
        $users=Delegate::join("car_levels","car_levels.id","delegates.car_level")
            ->join("countries","countries.id","delegates.country_id")
            ->orderBy('id','desc')
            ->where("delegates.created_at",">=",$newDateFrom)
            ->where("delegates.created_at","<=",$newDateTo)
            ->select("delegates.id","delegates.phone","delegates.email","delegates.active",
                "f_name","l_name",
                "delegates.suspend","countries.name as country_name","delegates.image",
                "car_color","car_num","car_levels.name as level")
            ->get();
        return view('cp.reports.delegatesInvoice',[
            'usercount'         =>$usercount,
            'users'             =>$users,
        ]);
    }

    public function driversInvoice(){
        $newDateFrom = date("Y-m-d", strtotime(Session::get("dateFrom")));
        $newDateTo = date("Y-m-d", strtotime(Session::get("dateTo")));
        $usercount=Driver::join("car_levels","car_levels.id","drivers.car_level")
            ->join("countries","countries.id","drivers.country_id")
            ->orderBy('id','desc')
            ->where("drivers.created_at",">=",$newDateFrom)
            ->where("drivers.created_at","<=",$newDateTo)
            ->count();
        $users=Driver::join("car_levels","car_levels.id","drivers.car_level")
            ->join("countries","countries.id","drivers.country_id")
            ->orderBy('id','desc')
            ->where("drivers.created_at",">=",$newDateFrom)
            ->where("drivers.created_at","<=",$newDateTo)
            ->select("drivers.id","drivers.phone","drivers.email","drivers.active",
                "f_name","l_name",
                "drivers.suspend","countries.name","drivers.image",
                "car_color","car_num","car_levels.name as level")
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

    public function shopsInvoice(){
        $newDateFrom = date("Y-m-d", strtotime(Session::get("dateFrom")));
        $newDateTo = date("Y-m-d", strtotime(Session::get("dateTo")));
        $usercount=Shop::join("categories","categories.id","shops.category_id")
            ->join("countries","countries.id","shops.country_id")
            ->orderBy('id','desc')
            ->where("shops.created_at",">=",$newDateFrom)
            ->where("shops.created_at","<=",$newDateTo)
            ->count();
        $users=Shop::join("categories","categories.id","shops.category_id")
            ->join("countries","countries.id","shops.country_id")
            ->orderBy('id','desc')
            ->where("shops.created_at",">=",$newDateFrom)
            ->where("shops.created_at","<=",$newDateTo)
            ->select("shops.id","shops.name","shops.phone","shops.email","shops.active",
                "shops.suspend","countries.name","shops.image",
                "categories.name as cat_name")
            ->get();
        return view('cp.reports.shopsInvoice',[
            'usercount'         =>$usercount,
            'users'             =>$users,
        ]);
    }

    public function ordersShopsInvoice(){
        $newDateFrom = date("Y-m-d", strtotime(Session::get("dateFrom")));
        $newDateTo = date("Y-m-d", strtotime(Session::get("dateTo")));
        $usercount=Order::with('user')
            ->where('department_id',2)
            ->where("created_at",">=",$newDateFrom)
            ->where("created_at","<=",$newDateTo)
            ->count();
        $users=Order::with('user')
            ->where('department_id',2)
            ->with('delegate')
            ->with(["order_products" => function ($query) {
                $query->with(["order_product_variations" => function ($query) {
                    $query->with('order_product_variation_options');
                }]);
            }])
            ->with("order_images")
            ->where("created_at",">=",$newDateFrom)
            ->where("created_at","<=",$newDateTo)
            ->get();
        foreach($users as $order){
            $order->order_status = OrderStatus::where('order_id',$order->id)->first();
        }
        return view('cp.reports.ordersShopsInvoice',[
            'usercount'         =>$usercount,
            'users'             =>$users,
        ]);
    }

    public function ordersNormalInvoice(){
        $newDateFrom = date("Y-m-d", strtotime(Session::get("dateFrom")));
        $newDateTo = date("Y-m-d", strtotime(Session::get("dateTo")));
        $usercount=Order::with('user')
            ->where('department_id',3)
            ->where("created_at",">=",$newDateFrom)
            ->where("created_at","<=",$newDateTo)
            ->count();
        $users=Order::with('user')
            ->where('department_id',3)
            ->with('delegate')
            ->with(["order_products" => function ($query) {
                $query->with(["order_product_variations" => function ($query) {
                    $query->with('order_product_variation_options');
                }]);
            }])
            ->with("order_images")
            ->where("created_at",">=",$newDateFrom)
            ->where("created_at","<=",$newDateTo)
            ->get();
        foreach($users as $order){
            $order->order_status = OrderStatus::where('order_id',$order->id)->first();
        }
        return view('cp.reports.ordersNormalInvoice',[
            'usercount'         =>$usercount,
            'users'             =>$users,
        ]);
    }


}
