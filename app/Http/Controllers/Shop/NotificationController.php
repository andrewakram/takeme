<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\CategoryRepositoryInterface;
use App\Models\Delegate;
use App\Models\Driver;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $nots = Notification::orderBy('id','desc')->get();
        $users = User::orderBy('id','desc')->select('id','name','email','phone')->get();
        $delegates = Delegate::orderBy('id','desc')->select('id','f_name','email','phone')->get();
        $drivers = Driver::orderBy('id','desc')->select('id','f_name','email','phone')->get();
        return view('cp.notifications.index',[
            'nots'=>$nots,
            'users'=>$users,
            'delegates'=>$delegates,
            'drivers'=>$drivers,
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required|',
            'body' => 'required|',
        ]);
        global $type;
        if($request->send_to == "users"){
            $users = User::get();
            $type = 0;
        }
        if($request->send_to == "delegates"){
            $users = Driver::get();
            $type = 1;
        }
        if($request->send_to == "drivers"){
            $users = Driver::get();
            $type = 2;
        }

        if($request->send_to == "all"){
            $users = User::get();
            $type = 3;
        }

        $add            = new Notification();
        $add->title     = $request->title;
        $add->body      = $request->body;
        $add->user_id   = isset($request->user_id) ? $request->user_id : 0;
        $add->type      = $type;
        $add->save();

        foreach ($users as $user){

            /*Notification::send("$user->token", $request->title , $request->body , "" );*/
            Notification::send("$user->token", $request->title , $request->body , "" ,0,null);
        }
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','data added successfully');
    }

    public function delete_not(Request $request,$id){
        /*Notification::where("id",$id)->forcedelete();*/
        Notification::whereId($id)->delete();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Notification deleted successfully');
    }


}
