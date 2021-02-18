<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\JazBenfit;
use DB;
use Route;
use Session;


class BenfitController extends Controller
{
    public function index(){
        $cities = JazBenfit::orderBy('id','desc')->get();
        return view('admin.benfits.index',['cities'=>$cities]);

    }



    public function edit_benfit(Request $request){
        /*$this->validate($request,[
            'ar_name' => 'required',
            'en_name' => 'required',
        ],[
            'ar_name.required' => 'Arabic text is required',
            'en_name.required' => 'English text is required',
        ]);*/
        JazBenfit::where('id', $request->id)
            ->update([
                'title1_en'      => $request->title1_en,
                'title1_ar'      => $request->title1_ar,
                'body1_en'      => $request->body1_en,
                'body1_ar'      => $request->body1_ar,

                'title2_en'      => $request->title2_en,
                'title2_ar'      => $request->title2_ar,
                'body2_en'      => $request->body2_en,
                'body2_ar'      => $request->body2_ar,

                'title3_en'      => $request->title3_en,
                'title3_ar'      => $request->title3_ar,
                'body3_en'      => $request->body3_en,
                'body3_ar'      => $request->body3_ar,

                'title4_en'      => $request->title4_en,
                'title4_ar'      => $request->title4_ar,
                'body4_en'      => $request->body4_en,
                'body4_ar'      => $request->body4_ar,
            ]);
        return back()->with('success','Data updated successfully');
    }
}
