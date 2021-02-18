<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Album;
use DB;
use Route;
use Session;


class AlbumController extends Controller
{
    public function index(){
        $cats = Category::orderBy('id','asc')
            ->where("parent_id",NULL)
            ->get();
        $cities = Album::join("categories","categories.id","albums.cat_id")
            ->orderBy('id','desc')
            ->select("albums.id","albums.smallimage","albums.largeimage",
                "albums.cat_id","categories.en_name","categories.ar_name")
            ->get();
        return view('admin.albums.index',[
            'cats'=>$cats,
            'cities'=>$cities
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'smallimage' => 'required',
            'largeimage' => 'required',
            'cat_id' => 'required',
        ]);
        $add            = new Album();
        $add->smallimage   = $request->smallimage;
        $add->largeimage   = $request->largeimage;
        $add->cat_id   = $request->cat_id;
        $add->save();
        return back()->with('success','Data added successfully');
    }

    public function deletealbum(Request $request,$id){
        Album::where('id', $id)->forcedelete();
        return back();
    }

}
