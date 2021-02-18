<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\CategoryRepositoryInterface;
use App\Models\CarLevel;
use App\Models\Driver;
use App\Models\Delegate;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class User2Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $users = User::orderBy('id','desc')
            ->get();
        return view('cp.users.index',['users'=>$users]);

    }

    public function index2(){
        $users = Driver::orderBy('id','desc')
            ->with('country')
            ->with('driver_car_levels')
            ->get();
        $carLevels = CarLevel::get();
        return view('cp.drivers.index',[
            'users'=>$users,
            'carLevels'=>$carLevels
        ]);

    }

    public function index3(){

        $users = Delegate::orderBy('id','desc')
            ->with('country')
            ->with('delegate_documents')
            ->with('orders')
            ->get();
        $carLevels = CarLevel::get();
        return view('cp.delegates.index',[
            'users'=>$users,
            'carLevels'=>$carLevels
        ]);
    }

    public function editCarLevel(Request $request)
    {
        Driver::whereId($request->user_id)
            ->update(["car_level" => $request->car_level ]);

        session()->flash('insert_message','تمت العملية بنجاح');
        return back();
    }

    public function accept_driver(Request $request,$id){
        CaptinInfo::where('user_id', $id)
            ->update(["accept" => 1]);
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Record updated successfully');
    }

    public function indexAdmin(){
        $users = User::orderBy('id','desc')
            ->where("is_captin",5) //as admin
            ->select("users.id","users.name","users.email","users.suspend")
            ->get();
        return view('cp.admins.index',['users'=>$users]);
    }



    public function editClientStatus(Request $request,$id)
    {
        $cat=User::where("id",$id)->first();
        if($cat->suspend == 1){
            User::where("id",$id)
                ->update(["suspend" => 0 ]);
        }else{
            User::where("id",$id)
                ->update(["suspend" => 1 ]);
        }
        session()->flash('insert_message','تمت العملية بنجاح');
        return back();
    }

    public function driverTrips($driver_id){
        $trips = Trip::where("driver_id",$driver_id)
            ->get();
        return view('cp.drivers.show',[
            'trips'=>$trips,
        ]);
    }

    public function finishedDriverTrips($driver_id){
        $trips = Trip::where("driver_id",$driver_id)
            ->where('status',3)
            ->get();
        return view('cp.drivers.show',[
            'trips'=>$trips,
        ]);
    }

    public function delegateOrders($delegate_id){
        $type = "كل الطلبات";
        $orders = Order::where('delegate_id',$delegate_id)
            ->with('user')
            ->with('delegate')
            ->with('offer')
            ->with('country')
            ->with(["order_products" => function ($query) {
                $query->with(["order_product_variations" => function ($query) {
                    $query->with('order_product_variation_options');
                }]);
            }])
            ->with("order_images")
            ->get();
        foreach($orders as $order){
            $order->order_status = OrderStatus::where('order_id',$order->id)->first();
        }

        return view('cp.delegates.show',compact('orders','type'));
    }

}
