<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\ContactUs;
use DB;
use Route;
use Session;


class ContactusController extends Controller
{
    public function index(){
        $cities = ContactUs::orderBy('id','desc')->get();
        return view('admin.contact_us.index',['cities'=>$cities]);

    }

    public function deleteContact(Request $request,$id){
        ContactUs::where('id', $id)->forcedelete();
        return back();
    }

}
