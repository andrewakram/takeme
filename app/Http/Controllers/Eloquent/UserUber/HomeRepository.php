<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 22/05/2019
 * Time: 01:53 م
 */

namespace App\Http\Controllers\Eloquent\UserUber;


use App\Http\Controllers\Interfaces\UserUber\HomeRepositoryInterface;
use App\Models\BankAccount;
use App\Models\Country;
use App\Models\CarLevel;
use App\Models\PromoCode;
use App\Models\CountryCarLevel;
use App\Models\Notification;
use App\Models\AboutUs;
use App\Models\Term;
use App\Models\Trip;
use App\Models\User;
use App\Models\CaptinInfo;
use Carbon\Carbon;
use DB;

class HomeRepository implements HomeRepositoryInterface
{
    public $model;

    /*public function __construct(Category $model)
    {
        $this->model = $model;
    }*/

    public function home($input,$country_id,$lang)
    {
        $cars=CarLevel::join("country_car_levels","car_levels.id","country_car_levels.car_level_id")
            ->where("country_car_levels.country_id",$country_id)
            ->select("car_levels.id","car_levels.name","car_levels.image","car_levels.description",
                "start_trip_unit", "distance_trip_unit")->get();
        //$taxiTypes = CarLevel::get();
        return $cars;
    }

    public function savedLocations($input,$country_id,$lang)
    {
        $savedLocations = [];
        $jwt = request()->header('jwt');
        if($jwt){
            $user = User::where('jwt',$jwt)->first();
            if($user){
                $savedLocations=Trip::orderBy('id','desc')
                    ->where("user_id",$user->id)
                    ->select("start_address","start_lat","start_lng")
                    ->take(3)->get();
                foreach($savedLocations as $savedLocation){
                    $location_name = explode(',',$savedLocation->start_address);
                    $savedLocation->start_address = $location_name[0];
                }
                return $savedLocations;
            }
        }
        return $savedLocations;
    }

    public function bankAccounts($input,$lang)
    {
        return BankAccount::get();
    }

    public function checkPromoCode($input,$country_id,$lang){
        $codeCheck=PromoCode::where("code",$input->code)
            ->select('id','code','value','type','country_ids','car_level_ids','expire_times','expire_at',$lang.'_desc as description' )
            ->first();
        if($codeCheck){
            if ( (int)strtotime($codeCheck->expire_at) < (int)strtotime(Carbon::now()->format('d F Y')) )
                return "code_expired";

            $car_level_ids = explode(',', $codeCheck->car_level_ids);
            if(!(in_array($input->car_level_id,$car_level_ids)))
                return "invalid_code";

            $trips=Trip::where("promo_id",$codeCheck->id)->get()->count();
            if($trips >= $codeCheck->expire_times)
                return "code_expired";

            $country_ids = explode(',', $codeCheck->country_ids);
            if(!(in_array($country_id,$country_ids)))
                return "invalid_code";

            unset($codeCheck->country_ids,$codeCheck->expire_times,
                $codeCheck->expire_at,$codeCheck->created_at,$codeCheck->updated_at);

            $codeCheck->type = (int)$codeCheck->type;
            if($input->type == 1){
                User::where('jwt',request()->header('jwt'))->update(['promo_code' => $codeCheck->id ]);
            }
            return $codeCheck;
        }
        return "invalid_code";
    }

    public function countries($lang)
    {
        return Country::orderBy("id","asc")
            ->select('id', 'name_'.$lang.' as name','image','code')
            ->where('active',1)
            ->get();
    }

    public function createTrip($lang,$input)
    {
        $taxiTypes = CarLevel::get();
        return $taxiTypes;
    }


    /*public function countries($lang)
    {
        $taxiTypes = CarLevel::get();
        return $taxiTypes;
    }*/





}
