<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Slider;
use DB;
use Route;
use Session;


class SliderController extends Controller
{
    public function index(){
        $cities = Slider::orderBy('id','desc')->get();
        return view('admin.sliders.index',['cities'=>$cities]);

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'title_ar' => 'required',
            'title_en' => 'required',
            'body_ar' => 'required',
            'body_en' => 'required',
            'image' => 'required',

        ]);
        $add            = new Slider();
        $add->title_ar   = $request->title_ar;
        $add->title_en   = $request->title_en;
        $add->body_ar   = $request->body_ar;
        $add->body_en   = $request->body_en;
        $add->image   = $request->image;
        $add->save();
        return back()->with('success','Data added successfully');
    }



    public function edit_slid(Request $request){
        /*$this->validate($request,[
            'ar_name' => 'required',
            'en_name' => 'required',
        ],[
            'ar_name.required' => 'Arabic text is required',
            'en_name.required' => 'English text is required',
        ]);*/
        Slider::where('id', $request->slider_id)
            ->update([
                'title_ar'      => $request->title_ar,
                'title_en'      => $request->title_en,
                'body_en'       => $request->body_en,
                'body_ar'       => $request->body_ar
            ]);
        $x=Slider::where('id', $request->slider_id)->first();
        if($request->image)
        {
            $x->image = $request->image;
            $x->save();
        }
        return back()->with('success','Data updated successfully');
    }

    public function deleteSlid(Request $request,$id){
        Slider::where('id', $id)->forcedelete();
        return back();
    }

}
