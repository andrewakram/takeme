<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 27/06/2019
 * Time: 11:06 ص
 */

namespace App\Http\Controllers\Eloquent\Captin;


use App\Models\CaptinInfo;
use App\Http\Controllers\Interfaces\Captin\TripRepositoryInterface;
use App\Models\ActiveRequest;
use App\Models\CarLevel;
use App\Models\Category;
use App\Models\Country;
use App\Models\CountryCarLevel;
use App\Models\Driver;
use App\Models\Notification;
use App\Models\Notify;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\PromoCode;
use App\Models\Rushhour;
use App\Models\ThirdCatOrder;
use App\Models\Trip;
use App\Models\TripDistance;
use App\Models\User;
use Carbon\Carbon;

class TripRepository implements TripRepositoryInterface
{
    public function changeStatus($input,$driver_id,$country_id,$lang)
    {
        $trip=Trip::where("id",$input->trip_id)->with('trip_paths')->first();
        if(!$trip)
            return false;
        if($input->status == 1)
        {
            if($trip->driver_id != 0){
                //trip accepted by another driver
                return false;
            }
            // 1=waiting_captin
            $trip->update([
                "status" => 1,
                "driver_id" => $driver_id
            ]);

            if ($lang =="en"){
                $title = 'Order Accepted ';
                $message = 'Order Accepted ';
            }else{
                $title = 'تم قبول الرحلة ';
                $message = 'تم قبول الرحلة ';
            }
            $driver=Driver::where("id",$driver_id)
                ->select('id','f_name','l_name','phone','image','car_level as car_model',
                    'front_car_image as car_image','car_level','car_num',
                    'car_color','color_name','lat','lng','rate','no_of_trips')
                ->first();
            //$driver->car_image = !empty($driver->car_image) ? asset('captins/car_images'.$driver->car_image) : asset('/default.png') ;
            $driver->trip_status=1;
            $driver->trip_msg=$message;

            $user=User::where("id",$trip->user_id)->first();

            $add            = new Notification();
            $add->title     = $title;
            $add->body      = $message;
            $add->user_id   = $trip->user_id;
            $add->type      = 0;
            $add->order_type   = 1;
            $add->order_id   = $trip->id;
            $add->save();
            $trip->driver = $driver;
            Notification::send($user->token, $title,
                $message, 0, 1,
                null, NULL, NULL,
                null,null,"$trip");

//            Notification::send(
//                "$user->token",
//                $title ,
//                $message ,
//                "" ,
//                1,
//                $trip,
//                null
//            );


        }
        ///////////////
         if($input->status == 5)
                {
                    // 1=driver_arrived
                    $trip->update([
                        "status" => 5,
                        "driver_id" => $driver_id
                    ]);

                    if ($lang =="en"){
                        $title = 'Order Accepted ';
                        $message = 'Order Accepted ';
                    }else{
                        $title = 'السائق وصل لموقعك';
                        $message =  'السائق وصل لموقعك';
                    }
                    $driver=Driver::where("id",$driver_id)
                        ->select('id','f_name','l_name','phone','image','car_level as car_model',
                            'front_car_image as car_image','car_level','car_num',
                            'car_color','color_name','lat','lng','rate','no_of_trips')
                        ->first();
                    //$driver->car_image = !empty($driver->car_image) ? asset('captins/car_images'.$driver->car_image) : asset('/default.png') ;
                    $driver->trip_status=5;
                    $driver->trip_msg=$message;

                    $user=User::where("id",$trip->user_id)->first();

                    $add            = new Notification();
                    $add->title     = $title;
                    $add->body      = $message;
                    $add->user_id   = $trip->user_id;
                    $add->type      = 0;
                    $add->order_type   = 1;
                    $add->order_id   = $trip->id;
                    $add->save();
                    $trip->driver = $driver;
                    Notification::send($user->token, $title,
                        $message, 0, 1,
                        null, NULL, NULL,
                        null,null,"$trip");

        //            Notification::send(
        //                "$user->token",
        //                $title ,
        //                $message ,
        //                "" ,
        //                1,
        //                $trip,
        //                null
        //            );


                }
        ///////////////
        if($input->status == 2)
        {
            // 2=trip_started ,
            $trip->update([
                "status" => 2,
            ]);

            if ($lang =="en"){
                $title = 'Trip started ';
                $message = 'Trip started ';
            }else{
                $title = 'تم بدء الرحلة ';
                $message = 'تم بدء الرحلة ';
            }
            $driver=Driver::where("id",$driver_id)
                ->select('id','f_name','l_name','phone','image','car_level as car_model',
                    'front_car_image as car_image','car_level','car_num',
                    'car_color','color_name','lat','lng','rate','no_of_trips')
                ->first();
            $driver->trip_status=2;
            $driver->trip_msg=$message;

            $user=User::where("id",$trip->user_id)->first();

            $add            = new Notification();
            $add->title     = $title;
            $add->body      = $message;
            $add->user_id   = $trip->user_id;
            $add->type      = 0;
            $add->order_type   = 1;
            $add->order_id   = $trip->id;
            $add->save();
            $trip->driver = $driver;
            Notification::send($user->token, $title,
                $message, 0, 1,
                null, NULL, NULL,
                null,null,"$trip");
            //
        }
        if($input->status == 3)
        {   // 3=trip_finished

            $is_in_rush_period = $this->check_rush_time($trip,$country_id);
            //calculate trip
            $this->calculateTripCost($input);

            $trip->update([
                "status" => 3,
            ]);
            $trip=Trip::where("id",$input->trip_id)->with('trip_paths')->first();
            if ($is_in_rush_period){
                $service = CountryCarLevel::where('car_level_id',$trip->car_level_id)->first();
                $start_trip_unit = $service->rush_start_trip_unit;
                $distance_trip_unit = $service->rush_distance_trip_unit;
                $waiting_trip_unit = $service->rush_waiting_trip_unit;
            }else{
                $service = CountryCarLevel::where('car_level_id',$trip->car_level_id)->first();
                $start_trip_unit = $service->start_trip_unit;
                $distance_trip_unit = $service->distance_trip_unit;
                $waiting_trip_unit = $service->waiting_trip_unit;
            }
            if($service) {
                $total = $start_trip_unit + (($trip->distance)
                        * $distance_trip_unit) +
                    (($trip->waiting_time)
                        * $waiting_trip_unit);

                if($trip->promo_id){
                    //the user entered promo_id in creating trip
                    $promo = $this->checkPromo($input->promo_id,$input->car_level_id,$trip->user->country_id,$lang);
                }else{
                    //the saved promo in users table
                    if(isset($trip->user->promo_code))
                        $promo = $this->checkPromo($trip->user->promo_code,$input->car_level_id,$trip->user->country_id,$lang);
                }

                if(!empty($promo) && !is_string($promo)){
                    //promo code set
                    if($promo->type == 0){
                        //promo fixed value
                        $trip_total = floor(($total) - ($promo->value));
                    }else{
                        //promo fixed value
                        $trip_total = floor(($total) - ($total)*($promo->value / 100) );
                    }
                    $trip->trip_total = $trip_total < 0 ? 0 : $trip_total;
                }else{
                    if($total > $trip->trip_total)
                        Trip::whereId($input->trip_id)->update(['trip_total' => $total]);
                }

                $user_wallet = User::whereId($trip->user_id)->select('wallet')->first();
                if($user_wallet->wallet < $total){
                    Trip::whereId($input->trip_id)->where('payment',2)->update(['payment' => 0]);
                } elseif($trip->payment == 2){
                    User::whereId($trip->user_id)->update(['wallet' => $user_wallet->wallet - $total]);
//                    send notification to user
                }
//***********************************
                Driver::where('id',$trip->driver_id)->update(['busy' => 0]);

            }
            if($trip->payment == 2){
                if ($lang == "en"){
                    $title = 'wallet has been discount by '.$total;
                    $message = 'wallet has been discount by '.$total;
                }else{
                    $title = 'تم خصم ' .$total. " من المحفظة";
                    $message ='تم خصم ' .$total. " من المحفظة";
                }
            }else{
                if ($lang == "en"){
                    $title = 'Order Finished ';
                    $message = 'Order Finished ';
                }else{
                    $title = 'تم انهاء الرحلة ';
                    $message ='تم انهاء الرحلة ';
                }
            }

            $driver=Driver::where("id",$driver_id)
                ->select('id','f_name','l_name','phone','image','car_level as car_model',
                    'front_car_image as car_image','car_level','car_num',
                    'car_color','color_name','lat','lng','rate','no_of_trips')
                ->first();
            $driver->trip_status=3;
            $driver->trip_msg=$message;
            $trip->trip_paths = [];
//unset($trip->trip_paths);
            $user=User::where("id",$trip->user_id)->first();

            $add            = new Notification();
            $add->title     = $title;
            $add->body      = $message;
            $add->user_id   = $trip->user_id;
            $add->order_id   = $trip->id;
            $add->type      = 0;
            $add->order_type   = 1;
            $add->save();
            $trip->driver = $driver;
            Notification::send($user->token, $title,
                $message, 0, 1,
                null, NULL, $user->wallet,
                null,null,"$trip");
            //
            User::whereId($trip->user_id)->update(['no_of_trips' => $user->no_of_trips + 1 ]);
            Driver::whereId($driver_id)->update(['no_of_trips' => $user->no_of_trips + 1 ]);
            //

        }
        if($input->status == 4)
        {
            // 4=trip_cancelled"
            $trip->update([
                "status" => 4,
                "canceled_by" => 1,
                "cancel_id" => $input->cancel_id,
                "cancel_reason" => $input->cancel_reason,
            ]);

            Driver::where("id",$driver_id)->update(["busy" => 0]);

            if ($lang =="en"){
                $title = 'Order Cancelled ';
                $message = 'Order Cancelled ';
            }else{
                $title = 'تم الغاء الرحلة ';
                $message ='تم الغاء الرحلة ';
            }
            $driver=User::join("captin_infos","captin_infos.user_id","users.id")
                ->where("users.id",$driver_id)
                ->select('users.id','name','phone','image','car_model','car_image','car_level','car_num','car_color','color_name','lat','lng','rate','no_of_trips')
                ->first();
            $driver->car_image = !empty($driver->car_image) ? asset('captins/car_images'.$driver->car_image) : asset('/default.png') ;
            $driver->trip_status=4;
            $driver->trip_msg=$message;

            $user=User::where("id",$trip->user_id)->first();

            $add            = new Notification();
            $add->title     = $title;
            $add->body      = $message;
            $add->user_id   = $trip->user_id;
            $add->trip_id   = $trip->id;
            $add->save();
            $trip->driver = $driver;
            Notification::send(
                "$user->token",
                $title ,
                $message ,
                "" ,
                1,
                $trip,
                null
            );


        }
        $trip = Trip::where("id",$input->trip_id)->with('user')->with('driver')->with('trip_paths')->first();
        //dd($trip);
        $trip->trip_id = $trip->id;
        if($trip->driver_id > 0){

            $driver=Driver::where("id",$driver_id)
                ->select('id','f_name','l_name','phone','image','car_level as car_model',
                    'front_car_image as car_image','car_level','car_num',
                    'car_color','color_name','lat','lng','rate','no_of_trips')
                ->first();
            $trip->driver = $driver;
        }
        return $trip;
    }

    public function calculateTripCost($input){
        $locations = $input->locations;
//        $locations = array('30.12449,31.375885','30.12449,31.375885',
//                    '30.1243502,31.3757953','30.1236198,31.3749196','30.1232079,31.374411','30.1221937,31.3731624','30.1218785,31.3727314','30.1215097,31.3721974','30.1204943,31.3709169','30.1188341,31.3689759','30.1160976,31.3664356','30.1127029,31.3671662','30.1112891,31.3695268','30.1098672,31.3710297','30.1087037,31.372808','30.1079401,31.3744095','30.1068789,31.3748766','30.1062727,31.3741457','30.1059321,31.3735014','30.1052669,31.3727638','30.104587,31.3719825','30.1041925,31.3720628','30.1042933,31.3726417','30.1039022,31.3730937','30.10353,31.3735209','30.1033384,31.3737587','30.1033406,31.37376');;
        $waiting_time=0;
        $total_distance=0;

        $count=count($locations);
        $locations=json_encode($locations);
        if($count>0) {
//            $firstLastLocation = array($locations[0], $locations[1], $locations[$count - 2], $locations[$count - 1]);
            $actualTotal = getDistanceLatLng($locations);
//            $total = getDistanceLatLng($firstLastLocation);
//            if ($actualTotal['time'] >= $total['time']) {
//                $waiting_time = ($actualTotal['time'] - $total['time']) / 60;
//            }
            $total_distance = $actualTotal['distance'] ;
        }

        //store in DB

        $trip_distance = Trip::whereId($input->trip_id)->first();
        if($trip_distance)
        {
            $trip_distance->trip_distance = $total_distance;
            $trip_distance->waiting_time = $waiting_time;
            $trip_distance->save();
        }
    }

    public function tripHistory($driver_id,$type,$key,$lang,$driver_country_id){
        //$type = 0=>all, 1=>cash, 2=>credit
        //$key = 0=>weekly, 1=>monthly, 2=>yearly
        $currentDate = Carbon::now();
        $lastWeek  = $currentDate->subDays(7);
        $lastmonth  = $currentDate->subDays(30);
        $lastYear  = $currentDate->subDays(365);
        $data = [];
        if($type == 0){ //$type= payment all
            if($key == 0){ //$key=weekly
                $trips=Trip::orderBy('id','desc')
                    ->where("country_id",$driver_country_id)
                    ->where("driver_id",$driver_id)
                    ->where("created_at",">",$lastWeek)
                    ->where("status",3)
                    ->with('driver')
                    ->with('user')
                    ->with(["trip_paths" => function($query) use($lang){
                        $query->select('id','status','address','lat','lng','trip_id');
                    }])->get();
            }elseif ($key == 1){ //$key=monthly
                $trips=Trip::orderBy('id','desc')
                    ->where("country_id",$driver_country_id)
                    ->where("driver_id",$driver_id)
                    ->where("created_at",">",$lastmonth)
                    ->where("status",3)
                    ->with('driver')
                    ->with('user')
                    ->with(["trip_paths" => function($query) use($lang){
                        $query->select('id','status','address','lat','lng','trip_id');
                    }])->get();
            }elseif ($key == 2){ //$key=$lastYear
                $trips=Trip::orderBy('id','desc')
                    ->where("country_id",$driver_country_id)
                    ->where("driver_id",$driver_id)
                    ->where("created_at",">",$lastmonth)
                    ->where("status",3)
                    ->with('driver')
                    ->with('user')
                    ->with(["trip_paths" => function($query) use($lang){
                        $query->select('id','status','address','lat','lng','trip_id');
                    }])->get();
            }
        }elseif ($type == 1){ //$type= payment cash
            if($key == 0){ //$key=weekly
                $trips=Trip::orderBy('id','desc')
                    ->where("country_id",$driver_country_id)
                    ->where("driver_id",$driver_id)
                    ->where("created_at",">",$lastWeek)
                    ->where("payment",0)
                    ->where("status",3)
                    ->with('driver')
                    ->with('user')
                    ->with(["trip_paths" => function($query) use($lang){
                        $query->select('id','status','address','lat','lng','trip_id');
                    }])->get();
            }elseif ($key == 1){ //$key=monthly
                $trips=Trip::orderBy('id','desc')
                    ->where("country_id",$driver_country_id)
                    ->where("driver_id",$driver_id)
                    ->where("created_at",">",$lastmonth)
                    ->where("payment",0)
                    ->where("status",3)
                    ->with('driver')
                    ->with('user')
                    ->with(["trip_paths" => function($query) use($lang){
                        $query->select('id','status','address','lat','lng','trip_id');
                    }])->get();
            }elseif ($key == 2){ //$key=$lastYear
                $trips=Trip::orderBy('id','desc')
                    ->where("country_id",$driver_country_id)
                    ->where("driver_id",$driver_id)
                    ->where("created_at",">",$lastmonth)
                    ->where("payment",0)
                    ->where("status",3)
                    ->with('driver')
                    ->with('user')
                    ->with(["trip_paths" => function($query) use($lang){
                        $query->select('id','status','address','lat','lng','trip_id');
                    }])->get();
            }
        }elseif ($type == 2){ //$type= payment online
            if($key == 0){ //$key=weekly
                $trips=Trip::orderBy('id','desc')
                    ->where("country_id",$driver_country_id)
                    ->where("driver_id",$driver_id)
                    ->where("created_at",">",$lastWeek)
                    ->where("payment",1)
                    ->where("status",3)
                    ->with('driver')
                    ->with('user')
                    ->with(["trip_paths" => function($query) use($lang){
                        $query->select('id','status','address','lat','lng','trip_id');
                    }])->get();
            }elseif ($key == 1){ //$key=monthly
                $trips=Trip::orderBy('id','desc')
                    ->where("driver_id",$driver_id)
                    ->where("country_id",$driver_country_id)
                    ->where("created_at",">",$lastmonth)
                    ->where("payment",1)
                    ->where("status",3)
                    ->with('driver')
                    ->with('user')
                    ->with(["trip_paths" => function($query) use($lang){
                        $query->select('id','status','address','lat','lng','trip_id');
                    }])->get();
            }elseif ($key == 2){ //$key=$lastYear
                $trips=Trip::orderBy('id','desc')
                    ->where("country_id",$driver_country_id)
                    ->where("driver_id",$driver_id)
                    ->where("created_at",">",$lastmonth)
                    ->where("payment",1)
                    ->where("status",3)
                    ->with('driver')
                    ->with('user')
                    ->with(["trip_paths" => function($query) use($lang){
                        $query->select('id','status','address','lat','lng','trip_id');
                    }])->get();
            }
        }

        $total = 0;
        foreach ($trips as $trip){
            $total = $total + $trip->trip_total;
            unset($trip->end_address,$trip->end_lat,$trip->end_lng);
        }

//        $trips=Trip::where("driver_id",$driver_id)
//            ->with('driver')
//            ->with('user')
//            ->with(["trip_paths" => function($query) use($lang){
//                $query->select('id','status','address','lat','lng','trip_id');
//            }])->get();
        $currency = (string)Country::whereId($driver_country_id)->first()->currency;
        $total = $total * 0.7;
        $data ['total_trips'] = sizeof($trips);;
        $data ['total_profits'] = number_format($total, 1, '.', '') . " $currency";;
        $data ['trips'] = $trips;

        return $data;
    }

    public function rateTrip($input,$driver_id,$is_captin,$lang){
        if($is_captin == 0){
            Trip::where("id",$input->trip_id)
                ->update([
                    "user_rate" =>$input->rate,
                    "user_comment" =>$input->comment,
                ]);
        }else{
            Trip::where("id",$input->trip_id)
                ->where("driver_id",$driver_id)->update([
                    "driver_rate" =>$input->rate,
                    "driver_comment" =>$input->comment,
                ]);
        }
    }

    public function updateStatus($input,$driver_id,$lang){
        CaptinInfo::where("user_id",$driver_id)
            ->update([
                "online" =>$input->online,
            ]);
    }

    public function collectMoney($input,$driver_id,$lang){
        $trip=Trip::where("id",$input->trip_id)->where("driver_id",$driver_id)->first();
        $user=User::where('id',$trip->user_id)->first();
        $user->wallet += ($input->money) - ($trip->trip_total);
        $user->save();
//        User::where('id',$trip->user_id)->update([
//            'wallet' => ($input->money) - ($trip->trip_total)
//        ]);

        $added_wallet_value = ($input->money) - ($trip->trip_total);
        if ($lang =="en"){
            $title = 'New Message ';
            $message = 'Your wallet raised by '. $added_wallet_value;
        }else{
            $title = 'رسالة جديدة';
            $message = 'تم اضافة ' . $added_wallet_value .'الي محفظتك';
        }
        Notification::send(
            "$user->token",
            $title ,
            $message ,
            "" ,
            1,
            null,
            null,
            $user->wallet
        );

    }

    public function check_rush_time($trip,$country_id)
    {
        $flag = false;
        if ($trip->type == 'urgent'){
            $trip_time = $trip->created_time;
        }else{
            $trip_time = $trip->time;
        }
        $rush_periods = Rushhour::where('country_id', $country_id)->get();
        if(sizeof($rush_periods)>0){
            foreach ($rush_periods as $rush_period){
//            dd($trip_time, $rush_period->to, $rush_period->from);
                if ($trip_time <= $rush_period->to && $trip_time >= $rush_period->from){
                    $flag = true;
                }
            }
            return $flag;
        }
        return false;

    }

    public function checkPromo($promo_id,$car_level_id,$country_id,$lang){
        $codeCheck=PromoCode::where("id",$promo_id)
            ->select('id','code','value','type','country_ids','car_level_ids','expire_times','expire_at',$lang.'_desc as description' )
            ->first();
        if($codeCheck){
            if ( (int)strtotime($codeCheck->expire_at) < (int)strtotime(Carbon::now()->format('d F Y')) )
                return "code_expired";

            $car_level_ids = explode(',', $codeCheck->car_level_ids);
            if(!(in_array($car_level_id,$car_level_ids)))
                return "invalid_code.";

            $trips=Trip::where("promo_id",$codeCheck->id)->get()->count();
            if($trips >= $codeCheck->expire_times)
                return "code_expired";

            $country_ids = explode(',', $codeCheck->country_ids);
            if(!(in_array($country_id,$country_ids)))
                return "invalid_code_";

            unset($codeCheck->country_ids,$codeCheck->expire_times,
                $codeCheck->expire_at,$codeCheck->created_at,$codeCheck->updated_at);

            $codeCheck->type = (int)$codeCheck->type;
            return $codeCheck;
        }
        return "invalid_code";
    }

    public function getCredits($request,$driver_id,$lang){
        global $total_trips;
        global $driver_credit;
        global $admin_credit;
        global $driver_has_money;
        global $admin_has_money;
        $total_trips = 0;
        $driver_credit = 0;
        $admin_credit = 0;
        $driver_has_money = 0;
        $admin_has_money = 0;
        $trips = Trip::where('driver_id',$driver_id)->get();
        foreach($trips as $trip){
            $total_trips = $total_trips + $trip->trip_total;
            if($trip->payment == 0){//cash
                $driver_has_money = $driver_has_money + $trip->trip_total;
            }else{//online & wallet
                $admin_has_money = $admin_has_money + $trip->trip_total;
            }
        }
        //dd($total_trips);
        //dd($driver_has_money);
        $driver_credit = $total_trips * 0.7;
        //$driver_credit = $driver_credit - $driver_has_money;
        $admin_credit = $total_trips * 0.3;
        //$admin_credit = $admin_credit - $driver_has_money;
        return $data=[
            'driver_credit' => number_format($driver_credit, 1, '.', ''),
            'admin_credit' => number_format($admin_credit, 1, '.', ''),
        ];
    }

}
