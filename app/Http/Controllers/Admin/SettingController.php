<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\CategoryRepositoryInterface;
use App\Models\AboutUs;
use App\Models\Admin;
use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $data = Admin::where('email','admin@admin.com')->first();
        $app_percent = $data->app_percent;
        $fee_percent = $data->fee_percent;

        return view('cp.settings.index',[
            'app_percent'=>$app_percent,
            'fee_percent'=>$fee_percent,
        ]);

    }


    public function edit_abouts(Request $request){
        $this->validate($request,[
            'app_percent' => 'required|',
            'fee_percent' => 'required|',
        ]);
        Admin::where('email','admin@admin.com')->update([
            'app_percent' => $request->app_percent,
            'fee_percent' => $request->fee_percent,
        ]);
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Terms updated successfully');
    }



}
