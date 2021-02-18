<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Interfaces\Admin\CategoryRepositoryInterface;
use App\Models\CarLevel;
use App\Models\Delegate;
use App\Models\ShopDelegate;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class User2Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:shop');
    }

    public function index(){
        $users = User::orderBy('id','desc')
            ->get();
        return view('cp.users.index',['users'=>$users]);

    }
    public function delegates(){
        $users = Delegate::join('shops_delegates','shops_delegates.delegate_id','delegates.id')
            ->where('shop_id',Auth::user()->id)
            ->get();
        return view('cp_shop.delegates.index',['users'=>$users]);

    }
    public function offers(){
        $offers = Slider::where('shop_id',Auth::user()->id)
            ->where('active',1)
            ->get();
        return view('cp_shop.offers.index',['offers'=>$offers]);

    }

    public function index2(){
        $users = User::join("captin_infos","captin_infos.user_id","users.id")
            ->join("countries","countries.id","users.country_id")
            ->join("car_levels","car_levels.id","captin_infos.car_level")
            ->orderBy('id','desc')
            ->where("is_captin",1)
            ->select("users.id","users.name","users.phone","users.email","users.active",
                "users.suspend","countries.name_ar","countries.name_en","users.is_captin",
                "users.image","accept","busy","online","driving_license","working_hours",
                "id_image_1","id_image_2","car_license_1","car_license_2","feesh",
                "car_color","car_num","car_model",'color_name','car_image',
                'car_levels.id as car_level_id','car_levels.name as car_level_name')
            ->get();
        $carLevels = CarLevel::get();
        return view('cp.drivers.index',[
            'users'=>$users,
            'carLevels'=>$carLevels
        ]);
    }

    public function editCarLevel(Request $request)
    {
        CaptinInfo::where("user_id",$request->user_id)
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

}
