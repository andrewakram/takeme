<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\CategoryRepositoryInterface;
use App\Models\AboutUs;
use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppExplanation extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $abouts = \App\Models\AppExplanation::orderBy('id','desc')
            ->get();
        return view('cp.app_explanations.index',[
            'abouts'=>$abouts,
        ]);

    }


    public function edit_abouts(Request $request){
        $this->validate($request,[
            'body_ar' => 'required|',
            'body_en' => 'required|',
        ]);
        $c=\App\Models\AppExplanation::where('id', $request->term_id)->first();
        $c->update($request->all());
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Terms updated successfully');
    }



}
