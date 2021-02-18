<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\JazPartener;
use Route;
use Session;


class PartenerController extends Controller
{
    public function index(){
        $cities = JazPartener::orderBy('id','desc')->get();
        return view('admin.parteners.index',['cities'=>$cities]);

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'link' => 'required',
            'image' => 'required',

        ]);
        $add            = new JazPartener();
        $add->link   = $request->link;
        $add->image   = $request->image;
        $add->save();
        return back()->with('success','Data added successfully');
    }



    public function edit_partener(Request $request){
        /*$this->validate($request,[
            'ar_name' => 'required',
            'en_name' => 'required',
        ],[
            'ar_name.required' => 'Arabic text is required',
            'en_name.required' => 'English text is required',
        ]);*/
        JazPartener::where('id', $request->slider_id)
            ->update([
                'link'      => $request->link,
            ]);
        $x=JazPartener::where('id', $request->id)->first();
        if($request->image)
        {
            $x->image = $request->image;
            $x->save();
        }
        return back()->with('success','Data updated successfully');
    }

    public function deletepartener(Request $request,$id){
        JazPartener::where('id', $id)->forcedelete();
        return back();
    }

}
