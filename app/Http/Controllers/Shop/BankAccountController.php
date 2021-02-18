<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Country;
use App\Models\Gov;
use Illuminate\Http\Request;
use App\Models\Rushhour;
use DB;
use Route;
use Session;


class BankAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $results = BankAccount::orderBy('id','desc')
            ->get();
        return view('cp.bank_accounts.index',[
            'results'=>$results,
        ]);

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'bank_name' => 'required',
            'account_no' => 'required',
            'image' => 'required',
        ]);
        $add                = new BankAccount();
        $add->bank_name     = $request->bank_name;
        $add->account_no    = $request->account_no;
        $add->image         = $request->image;
        $add->save();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Record added successfully');
    }



    public function edit_bank_account(Request $request){
//        $this->validate($request,[
//            'ar_bank_account' => 'required',
//            'en_bank_account' => 'required',
//            'is_captin' => 'required',
//        ]);
        $c=BankAccount::where('id', $request->bank_account_id)->first();
        $c->update($request->all());

        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Record updated successfully');
    }

    public function delete_bank_account(Request $request,$id){

        BankAccount::destroy($id);

        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Record deleted successfully');
    }

}
