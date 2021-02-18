<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\CategoryRepositoryInterface;
use App\Models\Term;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TermController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $terms = Term::orderBy('id','desc')
            ->get();
        return view('cp.terms.index',[
            'terms'=>$terms,
        ]);

    }



    public function edit_terms(Request $request){
        /*$this->validate($request,[
            'body_ar' => 'required',
            'body_en' => 'required',
        ]);*/
        $c=Term::where('id', $request->term_id)->first();
        $c->update($request->all());
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Terms updated successfully');
    }

}
