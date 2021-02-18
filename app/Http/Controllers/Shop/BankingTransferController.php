<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankingTransfer;
use App\Models\Country;
use App\Models\Gov;
use Illuminate\Http\Request;
use App\Models\Rushhour;
use DB;
use Route;
use Session;


class BankingTransferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $results = BankingTransfer::orderBy('id','desc')
            ->join('users','users.id','banking_transfers.user_id')
            ->select('banking_transfers.id','bank_name','transfer_no','transfer_value','banking_transfers.image',
                'users.id as user_id','name','email','phone')
            ->get();
        return view('cp.bank_transfers.index',[
            'results'=>$results,
        ]);

    }

//    public function store(Request $request)
//    {
//        $this->validate($request,[
//            'ar_reason' => 'required',
//            'en_reason' => 'required',
//            'is_captin' => 'required',
//        ]);
//        $add                = new BankingTransfer();
//        $add->ar_reason        = $request->ar_reason;
//        $add->en_reason        = $request->en_reason;
//        $add->is_captin     = $request->is_captin;
//        $add->save();
//        session()->flash('insert_message','تمت العملية بنجاح');
//        return back()->with('success','Record added successfully');
//    }
//
//
//
//    public function edit_reason(Request $request){
//        $this->validate($request,[
//            'ar_reason' => 'required',
//            'en_reason' => 'required',
//            'is_captin' => 'required',
//        ]);
//        $c=BankingTransfer::where('id', $request->reason_id)->first();
//        $c->update($request->all());
//
//        session()->flash('insert_message','تمت العملية بنجاح');
//        return back()->with('success','Record updated successfully');
//    }
//
//    public function delete_reason(Request $request,$id){
//
//        BankingTransfer::destroy($id);
//
//        session()->flash('insert_message','تمت العملية بنجاح');
//        return back()->with('success','Record deleted successfully');
//    }

}
