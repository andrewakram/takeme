<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\JazTeam;
use DB;
use Route;
use Session;


class TeamController extends Controller
{
    public function index(){
        $cities = JazTeam::orderBy('id','desc')->get();
        return view('admin.team.index',['cities'=>$cities]);

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'image' => 'required',
            'job' => 'required',
            'facebook' => 'required',
            'twitter' => 'required',
            'youtube' => 'required',
            'instgram' => 'required',
            'linkedin' => 'required',

        ]);
        $add            = new JazTeam();
        $add->name   = $request->name;
        $add->job   = $request->job;
        $add->facebook   = $request->facebook;
        $add->twitter   = $request->twitter;
        $add->youtube   = $request->youtube;
        $add->twitter   = $request->twitter;
        $add->instgram   = $request->instgram;
        $add->linkedin   = $request->linkedin;
        $add->image   = $request->image;
        $add->save();
        return back()->with('success','Data added successfully');
    }



    public function edit_team(Request $request){
        /*$this->validate($request,[
            'ar_name' => 'required',
            'en_name' => 'required',
        ],[
            'ar_name.required' => 'Arabic text is required',
            'en_name.required' => 'English text is required',
        ]);*/
        JazTeam::where('id', $request->id)
            ->update([
                'name'      => $request->name,
                'job'      => $request->job,
                'facebook'       => $request->facebook,
                'twitter'       => $request->twitter,
                'youtube'       => $request->youtube,
                'instgram'       => $request->instgram,
                'linkedin'       => $request->linkedin,
            ]);
        $x=JazTeam::where('id', $request->id)->first();
        if($request->image)
        {
            $x->image = $request->image;
            $x->save();
        }
        return back()->with('success','Data updated successfully');
    }

    public function deleteteam(Request $request,$id){
        JazTeam::where('id', $id)->forcedelete();
        return back();
    }

}
