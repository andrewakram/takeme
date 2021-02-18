<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CancellingReason;
use App\Models\Country;
use App\Models\Gov;
use Illuminate\Http\Request;
use App\Models\Rushhour;
use DB;
use Route;
use Session;


class ReasonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $results = CancellingReason::orderBy('id','desc')
            ->get();
        return view('cp.reasons.index',[
            'results'=>$results,
        ]);

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'ar_reason' => 'required',
            'en_reason' => 'required',
            'is_captin' => 'required',
        ]);
        $add                = new CancellingReason();
        $add->ar_reason        = $request->ar_reason;
        $add->en_reason        = $request->en_reason;
        $add->is_captin     = $request->is_captin;
        $add->save();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Record added successfully');
    }



    public function edit_reason(Request $request){
        $this->validate($request,[
            'ar_reason' => 'required',
            'en_reason' => 'required',
            'is_captin' => 'required',
        ]);
        $c=CancellingReason::where('id', $request->reason_id)->first();
        $c->update($request->all());

        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Record updated successfully');
    }

    public function delete_reason(Request $request,$id){

        CancellingReason::destroy($id);

        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Record deleted successfully');
    }

}
