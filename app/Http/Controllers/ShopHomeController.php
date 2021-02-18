<?php

namespace App\Http\Controllers;

use App\Models\CaptinInfo;
use App\Models\Category;
use App\Models\ComplainSuggests;
use App\Models\Country;
use App\Models\Delegate;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Shop;
use App\Models\Shop_detail;
use App\Models\Offer;
use App\Models\ShopDelegate;
use App\Models\Slider;
use App\Models\Trip;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ShopHomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $delegates=ShopDelegate::where('shop_id',Auth::guard('shop')->user()->id)->count();
        $products=Product::where('shop_id',Auth::guard('shop')->user()->id)->count();
        $offers=Slider::where('shop_id',Auth::guard('shop')->user()->id)->count();

        $orders = Order::where('shop_id',Auth::guard('shop')->user()->id)->count();
        $ordersIn24Hours = Order::where('shop_id',Auth::guard('shop')->user()->id)
            ->whereRaw("date(created_at) >= DATE(NOW()) - INTERVAL 1 DAY GROUP BY date(created_at)")
//            ->whereDate('created_at',Carbon::today())
            ->count();
        //dd(Carbon::today());
        $ordersIn7Days = Order::where('shop_id',Auth::guard('shop')->user()->id)
            ->where('created_at',">=", Carbon::now()->subDays(7))
            ->count();



        return view('cp_shop.home',
            compact('orders','ordersIn24Hours','ordersIn7Days',
                'offers','delegates','products'));
    }
}
