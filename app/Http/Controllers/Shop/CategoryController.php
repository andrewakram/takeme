<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\CategoryRepositoryInterface;
use App\Models\Category;
use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;

class CategoryController extends Controller
{
    protected $categoryRepository;
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cats = $this->categoryRepository->index();
        $cities=[];
        return view('cp.categories.index',compact('cats','cities'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function storeMainCat(Request $request)
    {
        $this->validate($request,[
            'ar_name' => 'required|unique:categories',
            'en_name' => 'required|unique:categories',
            'image' => 'required'
        ]);
        $this->categoryRepository->storeMainCat($request);
        $this->index();
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'ar_name' => 'required|unique:categories',
            'en_name' => 'required|unique:categories',
            'price' => '|numeric',
            'description' => 'required',
            'image' => 'required'
        ]);
        $id = (int)$request->parent_id;
        $type=$this->categoryRepository->storeSub($request);
        if($type == 2){
            return redirect(route('sub_cats',$id))
                ->with('success','Sub category added successfully');
        }
        if($type == 3){
            return redirect(route('third_cats',$id))
                ->with('success','Sub category added successfully');
        }
        if($type == 4){
            return redirect(route('fourth_cats',$id))
                ->with('success','Service added successfully');
        }

    }

    public function storeThird(Request $request)
    {
        $this->validate($request,[
            'ar_name' => 'required|unique:categories',
            'en_name' => 'required|unique:categories',
            'price' => 'required|numeric',
            'description' => 'required',
            'image' => 'required'
        ],[
            'ar_name.required' => 'Arabic text is required',
            'ar_name.unique' => 'Arabic text must be unique',
            'en_name.required' => 'English text is required',
            'en_name.unique' => 'English text must be unique',
            'price.required' => 'Price text is required',
            'price.numeric' => 'Price must be number',
            'description.required' => 'Description is required',
            'image.required' => 'Image is required',
        ]);
        $id = (int)$request->parent_id;
        $this->categoryRepository->storeThird($request);
        return redirect(route('third_cats',$id))->with('success','Third Category added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showSubCat($id)
    {
        $categories = $this->categoryRepository->getSubCat($id);
        $cities=City::select('id',Session::get('lang'). '_name as name')->get();
        $type=2;
        return view('admin.category.show',compact('categories','id','cities','type'));
    }

    public function showThirdCat($thirdId)
    {
        $categories = $this->categoryRepository->getThirdCat($thirdId);
        $cities=City::select('id',Session::get('lang'). '_name as name')->get();
        $type=3;
        return view('admin.category.show',compact('categories','thirdId','cities','type'));
    }

    public function showFourthCat($thirdId)
    {
        $categories = $this->categoryRepository->getFourthCat($thirdId);
        $cities=City::select('id',Session::get('lang'). '_name as name')->get();
        $type=4;
        return view('admin.category.show',compact('categories','thirdId','cities','type'));
    }

    public function editMainCat(Request $request)
    {
        $this->validate($request,[
            'ar_name' => 'required',
            'en_name' => 'required',
        ],[
            'ar_name.required' => 'Arabic text is required',
            'ar_name.unique' => 'Arabic text must be unique',
            'en_name.required' => 'English text is required',
            'en_name.unique' => 'English text must be unique',
        ]);
        $this->categoryRepository->editCat($request);
        return back()->with('success','Category updated successfully');
    }

    public function editCat(Request $request)
    {
        $this->validate($request,[
            'ar_name' => 'required',
            'en_name' => 'required',
            /*'price' => '|numeric',*/
            /*'description' => 'sometimes',
            'image' => 'sometimes'*/
        ],[
            'ar_name.required' => 'Arabic text is required',
            'ar_name.unique' => 'Arabic text must be unique',
            'en_name.required' => 'English text is required',
            'en_name.unique' => 'English text must be unique',
        ]);
        if($request->parent_id)
        {
            $id = (int)$request->parent_id;
            $this->categoryRepository->editCat($request);
            return redirect(route('sub_cats',$id))->with('success','Category updated successfully');
        }elseif($request->parent_third_id){
            $id = (int)$request->parent_third_id;
            $this->categoryRepository->editCat($request);
            return redirect(route('third_cats',$id))->with('success','Category updated successfully');
        }
    }

    public function editCatStatus(Request $request,$id)
    {
        $cat=Category::where("id",$id)->first();
        if($cat->active == 1){
            Category::where("id",$id)
                ->update(["active" => 0 ]);
        }else{
            Category::where("id",$id)
                ->update(["active" => 1 ]);
        }
        return back();
    }

    public function get_sub_category($parent)
    {
        $sub_cats = Category::where('parent_id',$parent)->get();
        return response()->json($sub_cats);
    }


    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
