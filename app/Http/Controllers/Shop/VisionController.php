<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\JazVision;
use DB;
use Route;
use Session;


class VisionController extends Controller
{
    public function index(){
        $cities = JazVision::orderBy('id','desc')->get();
        return view('admin.vision.index',['cities'=>$cities]);

    }



    public function edit_vision(Request $request){
        /*$this->validate($request,[
            'ar_name' => 'required',
            'en_name' => 'required',
        ],[
            'ar_name.required' => 'Arabic text is required',
            'en_name.required' => 'English text is required',
        ]);*/
        JazVision::where('id', $request->id)
            ->update([
                'body_en'      => $request->body_en,
                'vision1_en'      => $request->vision1_en,
                'vision2_en'       => $request->vision2_en,
                'vision3_en'       => $request->vision3_en,
                'vision4_en'       => $request->vision4_en,
                'body_ar'      => $request->body_ar,
                'vision1_ar'      => $request->vision1_ar,
                'vision2_ar'       => $request->vision2_ar,
                'vision3_ar'       => $request->vision3_ar,
                'vision4_ar'       => $request->vision4_ar
            ]);
        return back()->with('success','Data updated successfully');
    }
}
