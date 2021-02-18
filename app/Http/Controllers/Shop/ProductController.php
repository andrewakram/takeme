<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Gov;
use App\Models\Menu;
use App\Models\Option;
use App\Models\Product;
use App\Models\Variation;
use Illuminate\Http\Request;
use App\Models\Country;
use DB;
use Illuminate\Support\Facades\Auth;
use Route;
use Session;


class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $menus = Menu::where('shop_id',Auth::guard('shop')->user()->id)->get();
        $countries = Product::orderBy('id','desc')
            ->where('shop_id',Auth::guard('shop')->user()->id)
            ->with('menue')
            ->with(["variations" => function ($query) {
                $query->with(["options"]);
            }])
            ->with('variations')
            ->paginate(10);
//        dd($countries);
        return view('cp_shop.products.index',[
            'countries'=>$countries,
            'menus'=>$menus,
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'description' => 'required',
            'menu_id' => 'required',
        ]);
        $add            = new Product();
        $add->name   = $request->name;
        $add->description   = $request->description;
        $add->has_sizes   = isset($request->has_sizes) ? 1 : 0;
        $add->menu_id   = $request->menu_id;
        $add->shop_id   = Auth::guard('shop')->user()->id;
        $add->price_before   = $request->price_after;
        $add->price_after   = $request->price_after;
        $add->image   = $request->image;
        $add->save();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Country added successfully');
    }



    public function editProduct(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'description' => 'required',
            'menu_id' => 'required',
        ]);

        Product::where('id', $request->model_id)->update($request->except('_token','model_id','has_sizes'));
        if(isset($request->has_sizes)){
            Product::where('id', $request->model_id)->update(['has_sizes' => 1]);
        }else{
            Product::where('id', $request->model_id)->update(['has_sizes' => 0]);
        }

        if($request->image)
        {
            $x=Product::where('id', $request->model_id)->first();
            $x->image = $request->image;
            $x->save();
        }


        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Country updated successfully');
    }

    public function editVariation(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'type' => 'required',
        ]);

        if(isset($request->required)){
            $required = 1;
        }else{
            $required = 0;
        }
        Variation::whereId($request->variation_id)->update([
            'name' => $request->name,
            'required' => $required,
            'type' => $request->type,
        ]);
        Option::where('variation_id',$request->variation_id)->update([
            'type' => $request->type
        ]);

        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Country updated successfully');
    }

    public function editOption(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'price' => 'required',
        ]);
        Option::whereId($request->option_id)->update([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Country updated successfully');
    }

    public function deleteProduct(Request $request)
    {
        Product::whereId($request->model_id)->delete();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back();
    }

    public function addVariation(Request $request)
    {
        //dd($request->all());
        $this->validate($request,[
            'name' => 'required',
            'type' => 'required',
        ]);
        $add            = new Variation();
        $add->name   = $request->name;
        $add->required   = isset($request->required) ? 1 : 0;
        $add->type   = $request->type;
        $add->product_id   = $request->product_id;
        $add->save();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Country added successfully');
    }

    public function deleteVariation(Request $request)
    {
        Variation::whereId($request->model_id)->delete();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back();
    }

    public function deleteOption(Request $request)
    {
        Option::whereId($request->model_id)->delete();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back();
    }

    public function addOption(Request $request)
    {
        //dd($request->all());
        $this->validate($request,[
            'name' => 'required',
            'price' => 'required',
        ]);
        $variation = Variation::whereId($request->variation_id)->first();
        $add            = new Option();
        $add->name   = $request->name;
        $add->price   = $request->price;
        $add->type   = $variation->type;
        $add->variation_id   = $request->variation_id;
        $add->save();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Country added successfully');
    }

}
