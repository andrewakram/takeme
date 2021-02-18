<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\JazService;
use DB;
use Route;
use Session;


class ServiceController extends Controller
{
    public function index(){
        $cities = JazService::orderBy('id','desc')->get();
        return view('admin.services.index',['cities'=>$cities]);

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name_ar' => 'required',
            'name_en' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
            'image' => 'required',

        ]);
        $add            = new JazService();
        $add->name_ar   = $request->name_ar;
        $add->name_en   = $request->name_en;
        $add->description_ar   = $request->description_ar;
        $add->description_en   = $request->description_en;
        $add->image   = $request->image;
        $add->save();
        return back()->with('success','Data added successfully');
    }

    public function edit_service(Request $request){
        /*$this->validate($request,[
            'ar_name' => 'required',
            'en_name' => 'required',
        ],[
            'ar_name.required' => 'Arabic text is required',
            'en_name.required' => 'English text is required',
        ]);*/
        JazService::where('id', $request->id)
            ->update([
                'name_ar'      => $request->name_ar,
                'name_en'      => $request->name_en,
                'description_ar'       => $request->description_ar,
                'description_en'       => $request->description_en
            ]);
        $x=JazService::where('id', $request->id)->first();
        if($request->image)
        {
            $x->image = $request->image;
            $x->save();
        }
        return back()->with('success','Data updated successfully');
    }

    public function deleteservice(Request $request,$id){
        JazService::where('id', $id)->forcedelete();
        return back();
    }

}
