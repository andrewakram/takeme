<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CancellingReason;
use App\Models\Country;
use App\Models\Gov;
use App\Models\Issue;
use Illuminate\Http\Request;
use App\Models\Rushhour;
use DB;
use Route;
use Session;


class IssueController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $results = Issue::orderBy('id','desc')
            ->get();
        return view('cp.issues.index',[
            'results'=>$results,
        ]);

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'ar_issue' => 'required',
            'en_issue' => 'required',
            'is_captin' => 'required',
        ]);
        $add                = new Issue();
        $add->ar_issue        = $request->ar_issue;
        $add->en_issue        = $request->en_issue;
        $add->is_captin     = $request->is_captin;
        $add->save();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Record added successfully');
    }



    public function edit_issue(Request $request){
        $this->validate($request,[
            'ar_issue' => 'required',
            'en_issue' => 'required',
            'is_captin' => 'required',
        ]);
        $c=Issue::where('id', $request->issue_id)->first();
        $c->update($request->all());

        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Record updated successfully');
    }

    public function delete_issue(Request $request,$id){

        Issue::destroy($id);

        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Record deleted successfully');
    }

}
