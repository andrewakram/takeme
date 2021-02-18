<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 18/06/2019
 * Time: 03:52 م
 */

namespace App\Http\Controllers\Eloquent\App;


use App\Http\Controllers\Interfaces\App\AppRepositoryInterface;
use App\Models\AboutUs;
use App\Models\Admin;
use App\Models\AppExplanation;
use App\Models\CarType;
use App\Models\ComplainSuggests;
use App\Models\ContactUs;
use App\Models\Country;
use App\Models\City;
use App\Models\NationalType;
use App\Models\Notification;
use App\Models\TermCondition;
use App\Models\Term;

class AppRepository implements AppRepositoryInterface
{
    public function getFeePercent()
    {
        return Admin::where('email',"admin@admin.com")->select('fee_percent')->first()->fee_percent;
//         dd($country);
//          if(is_array($country))
//              return "aaa";
//        dd($country);
//        $country = implode(",", $country);
//        return $country;
    }

    public function countries()
    {
        return Country::where('active',1)->get();
    }

    public function countriesCodes()
    {
        return $country = Country::where('active',1)->pluck('code_name');
//         dd($country);
//          if(is_array($country))
//              return "aaa";
//        dd($country);
//        $country = implode(",", $country);
//        return $country;
    }

    public function carTpes()
    {
        return CarType::get();
    }

    public function nationalTypes()
    {
        return NationalType::get();
    }

    public function cities($input)
    {
        return City::where('country_id',$input->country_id)->get();
    }

    public function complainAndSuggestion($input)
    {
        $array = array(
          'type'=> $input->type,
          'user_id' => $input->user_id,
          'title' => $input->title,
          'description' => $input->description,
        );
        ComplainSuggests::create($array);
        return true;
    }

    public function aboutUs()
    {
        return new AboutUs;
    }

    public function termCondition()
    {
        return new Term();
    }

    public function appExplanation()
    {
        return new AppExplanation();
    }

    public function contactUs($request)
    {
        ContactUs::create($request->all());
    }

    public function getNotifications($request,$user_id, $lang = 'ar'){
        //type =>>>0=>user, 1=>delegate, 2=>driver, 3=>all
        //order_type =>>> 0=>witout, 1=>trip, 2=>shops_order, 3=>normal_order
        return Notification::where('user_id',$user_id)
            ->where('type',$request->type)
            ->where('order_type',$request->order_type)
//            ->select('title','body','created_at')
            ->get();
    }
}
