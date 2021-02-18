<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\ClientReivew;
use DB;
use Route;
use Session;


class ReviewController extends Controller
{
    public function index(){
        $cities = ClientReivew::orderBy('id','desc')->get();
        return view('admin.client_reviews.index',['cities'=>$cities]);

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'comment_ar' => 'required',
            'comment_en' => 'required',
            'image' => 'required',

        ]);
        $add            = new ClientReivew();
        $add->name   = $request->name;
        $add->comment_ar   = $request->comment_ar;
        $add->comment_en   = $request->comment_en;
        $add->image   = $request->image;
        $add->save();
        return back()->with('success','Data added successfully');
    }



    public function edit_review(Request $request){
        /*$this->validate($request,[
            'ar_name' => 'required',
            'en_name' => 'required',
        ],[
            'ar_name.required' => 'Arabic text is required',
            'en_name.required' => 'English text is required',
        ]);*/
        ClientReivew::where('id', $request->id)
            ->update([
                'name'      => $request->name,
                'comment_en'       => $request->comment_en,
                'comment_ar'       => $request->comment_ar
            ]);
        $x=ClientReivew::where('id', $request->id)->first();
        if($request->image)
        {
            $x->image = $request->image;
            $x->save();
        }
        return back()->with('success','Data updated successfully');
    }

    public function deletereview(Request $request,$id){
        ClientReivew::where('id', $id)->forcedelete();
        return back();
    }

}
