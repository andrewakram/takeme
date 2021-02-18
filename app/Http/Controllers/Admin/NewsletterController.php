<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Newsletter;
use DB;
use Route;
use Session;


class NewsletterController extends Controller
{
    public function index(){
        $cities = Newsletter::orderBy('id','desc')->get();
        return view('admin.newsletter.index',['cities'=>$cities]);

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'email' => 'required',
        ]);
        $add            = new Newsletter();
        $add->email   = $request->email;
        $add->save();
        return back()->with('success','Data added successfully');
    }



    public function edit_newsletter(Request $request){
        /*$this->validate($request,[
            'ar_name' => 'required',
            'en_name' => 'required',
        ],[
            'ar_name.required' => 'Arabic text is required',
            'en_name.required' => 'English text is required',
        ]);*/
        Newsletter::where('id', $request->id)
            ->update([
                'email'      => $request->email,
                ]);

        return back()->with('success','Data updated successfully');
    }

    public function deletenewsletter(Request $request,$id){
        Newsletter::where('id', $id)->forcedelete();
        return back();
    }

}
