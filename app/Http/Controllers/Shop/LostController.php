<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lost;
use App\Models\Country;
use App\Models\Gov;
use Illuminate\Http\Request;
use App\Models\Rushhour;
use DB;
use Route;
use Session;


class LostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $results = Lost::orderBy('id','desc')
            ->get();
        return view('cp.losts.index',[
            'results'=>$results,
        ]);

    }

    public function store(Request $request)
    {
        // $this->validate($request,[
        //     'ar_lost' => 'required',
        //     'en_lost' => 'required',
        //     'is_captin' => 'required',
        // ]);
        $add                = new Lost();
        $add->ar_lost        = $request->ar_lost;
        $add->en_lost        = $request->en_lost;
//        $add->is_captin     = $request->is_captin;
        $add->save();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Record added successfully');
    }



    public function edit_lost(Request $request){
        $this->validate($request,[
            'ar_lost' => 'required',
            'en_lost' => 'required',
//            'is_captin' => 'required',
        ]);
        $c=Lost::where('id', $request->lost_id)->first();
        $c->update($request->all());

        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Record updated successfully');
    }

    public function delete_lost(Request $request,$id){

        Lost::destroy($id);

        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Record deleted successfully');
    }

}
