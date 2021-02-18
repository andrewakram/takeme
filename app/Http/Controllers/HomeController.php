<?php

namespace App\Http\Controllers;

use App\Models\CaptinInfo;
use App\Models\Category;
use App\Models\City;
use App\Models\ComplainSuggests;
use App\Models\Country;
use App\Models\Delegate;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Review;
use App\Models\Shop;
use App\Models\Shop_detail;
use App\Models\Offer;
use App\Models\Slider;
use App\Models\Trip;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $cats=Category::count();
        $shops=Shop::count();
        $users=User::count();
        $delegates=Delegate::count();
        $offers=Slider::count();
        $nots=Notification::count();
        $shops_orders=Order::where('department_id',2)->count();
        $normal_orders=Order::where('department_id',3)->count();

        $rates=0;

        $users_charts = DB::SELECT("select id, count(*) as count,
            date(created_at) as date from users
            WHERE  date(created_at) >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY date(created_at),id");

        $drivers_charts = DB::SELECT("select id, count(*) as count,
            date(created_at) as date from drivers
            WHERE  date(created_at) >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY date(created_at),id");

        $delegates_charts = DB::SELECT("select id, count(*) as count,
            date(created_at) as date from delegates
            WHERE  date(created_at) >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY date(created_at),id");

        $shops_charts = DB::SELECT("select id, count(*) as count,
            date(created_at) as date from shops
            WHERE  date(created_at) >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY date(created_at),id");

        $shops_orders_charts = DB::SELECT("select id, count(*) as count,
            date(created_at) as date from orders
            WHERE  
                date(created_at) >= DATE(NOW()) - INTERVAL 7 DAY 
            AND 
                department_id = 2
            GROUP BY date(created_at),id");

        $normal_orders_charts = DB::SELECT("select id, count(*) as count,
            date(created_at) as date from orders
            WHERE  
                date(created_at) >= DATE(NOW()) - INTERVAL 7 DAY 
            AND 
                department_id = 2
            GROUP BY date(created_at),id");

        $offers_charts = DB::SELECT("select id, count(*) as count,
            date(created_at) as date from sliders
            WHERE  `active`=1 AND date(created_at) >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY date(created_at),id");
//dd($shops_charts);
        $rates_charts = [];
        ////////
        $drivers=CaptinInfo::count();
        $urgentTrips=Trip::where("type","urgent")->count();
        $scheduledTrips=Trip::where("type","scheduled")->count();
        $countries=Country::count();
        $cities=City::count();
        $comolains_suggestions=ComplainSuggests::count();

        $urgentTrips_charts = DB::SELECT("select id,count(*) as count,
            date(created_at) as date from trips
            WHERE  `type`='urgent' AND date(created_at) >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY date(created_at),id");

        $scheduledTrips_charts = DB::SELECT("select id,count(*) as count,
            date(created_at) as date from trips
            WHERE  `type`='scheduled' AND date(created_at) >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY date(created_at),id");


        return view('cp.home',
            compact('cats','shops','users','offers','nots','rates','delegates',
                'users_charts','drivers_charts','delegates_charts','shops_charts',
                'offers_charts','rates_charts',
                'drivers','urgentTrips','scheduledTrips','comolains_suggestions',
                'countries','cities','urgentTrips_charts','scheduledTrips_charts',
                'shops_orders_charts','normal_orders_charts',
                'shops_orders','normal_orders'
            ));
    }
}
