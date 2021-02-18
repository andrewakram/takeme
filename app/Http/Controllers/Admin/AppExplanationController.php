<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\CategoryRepositoryInterface;
use App\Models\AboutUs;
use App\Models\AppExplanation;
use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppExplanationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $abouts = AppExplanation::orderBy('id','desc')
            ->get();
        return view('cp.app_explanations.index',[
            'abouts'=>$abouts,
        ]);

    }

    public function store(Request $request){
        $this->validate($request,[
            'ar_title' => 'required|',
            'en_title' => 'required|',
            'ar_body' => 'required|',
            'en_body' => 'required|',
        ]);
        AppExplanation::create($request->all());
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Terms updated successfully');
    }


    public function edit_explains(Request $request){
        $this->validate($request,[
            'ar_title' => 'required|',
            'en_title' => 'required|',
            'ar_body' => 'required|',
            'en_body' => 'required|',
        ]);
        $c=AppExplanation::where('id', $request->model_id)->first();
        $c->update($request->all());
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Terms updated successfully');
    }



}
