<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 7/13/2019
 * Time: 8:07 PM
 */

namespace App\Http\Controllers\Eloquent\Admin;


use App\Http\Controllers\Interfaces\Admin\CategoryRepositoryInterface;
use App\Models\Category;
use App\Models\CityCategory;
use App\Models\City;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function index()
    {
        return Category::get();
    }

    public function getSubCat($id)
    {
        return Category::where('type',2)->where('parent_id',$id)->get();

    }

    public function getThirdCat($id)
    {
        return Category::where('type',3)->where('parent_id',$id)->get();
    }

    public function getFourthCat($id)
    {
        return Category::where('type',4)->where('parent_id',$id)->get();
    }

    public function storeMainCat($input)
    {
        Category::create([
            'type' => '1',
            'ar_name' => $input->ar_name,
            'en_name' => $input->en_name,
            'image' => $input->image
        ]);
        return Category::where('type',1)->where('parent_id',null)->get();
    }

    public function storeSub($input)
    {
        global $type;
        global $parent;
        $type=1;
        $parent=Category::where('id',$input->parent_id)->first();
        if($parent->type == 1){
            $type=2;
            $mainCat=$input->parent_id;
        }
        if($parent->type == 2){
            $type=3;
            $mainCat=$parent->main_cat;
        }
        if($parent->type == 3){
            $type=4;
            $mainCat=$parent->main_cat;
        }
        Category::create([
            'type' => $type,
            'parent_id' => $input->parent_id,
            'main_cat' => $mainCat,
            'ar_name' => $input->ar_name,
            'en_name' => $input->en_name,
            'price' => $input->price,
            'description' => $input->description,
            'image' => $input->image,
            'has_period' => $input->has_period
        ]);
        $cat=Category::where("ar_name",$input->ar_name)
            ->where("en_name",$input->en_name)
            ->where("parent_id",$input->parent_id)
            ->first();
        foreach($input->city as $key => $value){
            $add            = new CityCategory();
            $add->city_id   = $value;
            $add->category_id   = $cat->id;
            $add->save();
        }
        return $type;

    }

    public function storeThird($input)
    {
        Category::create([
            'type' => '3',
            'parent_id' => $input->parent_id,
            'price' => $input->price,
            'ar_name' => $input->ar_name,
            'en_name' => $input->en_name,
            'image' => $input->image,
            'has_period' => $input->has_period
        ]);
    }

    public function editMainCat($input)
    {
        $category = Category::whereId($input->cat_id)->first();
        $category->ar_name = $input->ar_name;
        $category->en_name = $input->en_name;

        if($input->image)
            $category->image = $input->image;

        $category->save();
    }

    public function editCat($input)
    {
        $category = Category::whereId($input->cat_id)->first();
        $category->ar_name = $input->ar_name;
        $category->en_name = $input->en_name;

        if($input->image)
            $category->image = $input->image;

        if($input->price)
            $category->price = $input->price;

        if($input->description)
            $category->description = $input->description;

        if($input->has_period)
            $category->has_period = $input->has_period;


        $category->save();

        CityCategory::where("category_id",$input->cat_id)->delete();
        if($input->city){
            foreach($input->city as $key => $value) {
            $add = new CityCategory();
            $add->city_id = $value;
            $add->category_id = $input->cat_id;
            $add->save();
        }
        }



    }
}
