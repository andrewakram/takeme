<?php

namespace App\Http\Controllers\Eloquent\UserUber;

use App\Models\CaptinInfo;
use App\Http\Controllers\Interfaces\UserUber\TripRepositoryInterface;
use App\Http\Requests\Request;
use App\Models\CancellingReason;
use App\Models\ChatImage;
use App\Models\CountryCarLevel;
use App\Models\Driver;
use App\Models\Location;
use App\Models\MessageImage;
use App\Models\Notification;
use App\Models\PromoCode;
use App\Models\Trip;
use App\Models\TripPath;
use App\Models\User;
use App\Models\Message;
use Carbon\Carbon;
use DB;

class TripRepository implements TripRepositoryInterface
{
    /*public $model;*/
//    public function __construct(Request $request)
//    {
//        $this->model = $model;
//    }
    public function deleteTrip($input, $user_id, $lang)
    {
        Trip::where('id', $input->trip_id)
            ->where('user_id', $user_id)
            ->where('driver_id', NULL)
            ->delete();
        return true;
    }

    public function calculateTripPrices($input, $country_id, $lang)
    {
        $latitudeFrom = $input->start_lat;
        $longitudeFrom = $input->start_lng;
        $latitudeTo = $input->end_lat;
        $longitudeTo = $input->end_lng;
        $earthRadius = 6371;
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        $angle2 = $angle * $earthRadius;

        $cars = CountryCarLevel::join("car_levels", "car_levels.id", "country_car_levels.car_level_id")
            ->where("country_car_levels.country_id", $country_id)
            ->select("car_levels.id", "car_levels.name", "car_levels.image", "car_levels.description",
                "start_trip_unit",
                "distance_trip_unit")->get();

        foreach ($cars as $car) {
            $car->distance = $angle2;

        }
        return $cars;
    }

    public function addLocation($input, $user_id, $lang)
    {
        $add = new Location();
        $add->title = isset($input->title) ? $input->title : '';
        $add->lat = $input->lat;
        $add->lng = $input->lng;
        $add->address = $input->address;
        $add->user_id = $user_id;
        $add->save();
    }

    public function getLocations($user_id, $lang)
    {
        return Location::orderBy("id", "desc")->where("user_id", $user_id)->get();
    }

    public function cancellingReasons($is_captin, $lang)
    {
        return CancellingReason::where("is_captin", $is_captin)
            ->select('id', $lang . '_reason as reason')
            ->get();
    }

    public function checkPromo($promo_id, $car_level_id, $country_id, $lang)
    {
        $codeCheck = PromoCode::where("id", $promo_id)
            ->select('id', 'code', 'value', 'type', 'country_ids', 'car_level_ids', 'expire_times', 'expire_at', $lang . '_desc as description')
            ->first();
        if ($codeCheck) {
            if ((int)strtotime($codeCheck->expire_at) < (int)strtotime(Carbon::now()->format('d F Y')))
                return "code_expired";

            $car_level_ids = explode(',', $codeCheck->car_level_ids);
            if (!(in_array($car_level_id, $car_level_ids)))
                return "invalid_code.";

            $trips = Trip::where("promo_id", $codeCheck->id)->get()->count();
            if ($trips >= $codeCheck->expire_times)
                return "code_expired";

            $country_ids = explode(',', $codeCheck->country_ids);
            if (!(in_array($country_id, $country_ids)))
                return "invalid_code_";

            unset($codeCheck->country_ids, $codeCheck->expire_times,
                $codeCheck->expire_at, $codeCheck->created_at, $codeCheck->updated_at);

            $codeCheck->type = (int)$codeCheck->type;
            return $codeCheck;
        }
        return "invalid_code";
    }

    public function createTrip($input, $user_id, $userlat, $userlng, $lang,$user_country_id)
    {
        $driver_trip =
            Trip::filterbylatlng($input->start_lat, $input->start_lng, 10000, 'drivers', $input->car_level_id,$user_country_id);
//return($user_country_id);
        if (sizeof($driver_trip) > 0) {
            $add = new Trip();
            $add->order_num = $user_id . time();
            $add->type = $input->type;
            $add->user_id = $user_id;
            $add->country_id = $user_country_id;
            /*$add->driver_id     = $driver_trip->id;*/
            $add->car_level_id = $input->car_level_id;
            $add->start_address = $input->start_address;
            $add->start_lat = $input->start_lat;
            $add->start_lng = $input->start_lng;
            /*$add->end_address   = $input->end_address;
            $add->end_lat       = $input->end_lat;
            $add->end_lng       = $input->end_lng;*/
            $add->payment = $input->payment;
            $add->save();
            $createdTripId = Trip::orderBy('id', 'desc')->where('user_id', $user_id)->first()->id;

//            TripPath::create([
//                'trip_id' => $createdTripId,
//                'status' => 0,
//                'address' => $input->start_address,
//                'lat' => $input->start_lat,
//                'lng' => $input->start_lng,
//            ]);
            for ($i = 0; $i < count($input->address); $i++) {
                TripPath::create([
                    'trip_id' => $createdTripId,
                    'status' => $i + 1,
                    'address' => $input->address[$i],
                    'lat' => $input->lat[$i],
                    'lng' => $input->lng[$i],
                ]);
            }

            global $thisTrip;
            $thisTrip = Trip::orderBy("id", "desc")
                ->where("user_id", $user_id)
                ->with(["trip_paths" => function ($query) use ($lang) {
                    $query->select('id', 'status', 'address', 'lat', 'lng', 'trip_id');
                }])->with('user')
                ->first();
            foreach ($driver_trip as $driver) {


                $driver->average_rate = Trip::where("driver_id", $driver->id)->avg('user_rate');

                $driver->trip_id = $thisTrip->id;
                $driver->start_address = $input->start_address;
                $driver->start_lat = $input->start_lat;
                $driver->start_lng = $input->start_lng;
                /*$driver->end_address = $input->end_address;
                $driver->end_lat = $input->end_lat;
                $driver->end_lng = $input->end_lng;*/
                //calculate distance
                global $distance;
                $distance = 0;
                global $promo;
                $distance += Trip::calc_distance($thisTrip->trip_paths[0]->lat, $thisTrip->trip_paths[0]->lng,
                    $thisTrip->trip_paths[1]->lat, $thisTrip->trip_paths[1]->lng);
                if (isset($thisTrip->trip_paths[2]->lat))
                    $distance += Trip::calc_distance($thisTrip->trip_paths[1]->lat, $thisTrip->trip_paths[1]->lng,
                        $thisTrip->trip_paths[2]->lat, $thisTrip->trip_paths[2]->lng);

                //check validation of promo code
                if ($input->promo_id) {
                    //the user entered promo_id in creating trip
                    $promo = $this->checkPromo($input->promo_id, $input->car_level_id, $thisTrip->user->country_id, $lang);
                } else {
                    //the saved promo in users table
                    if (isset($thisTrip->user->promo_code))
                        $promo = $this->checkPromo($thisTrip->user->promo_code, $input->car_level_id, $thisTrip->user->country_id, $lang);
                }

                //
                $calc_data = CountryCarLevel::where('country_id', $thisTrip->user->country_id)
                    ->where('car_level_id', $input->car_level_id)->first();

                if (!empty($promo) && !is_string($promo)) {
                    //promo code set
                    if ($promo->type == 0) {
                        //promo fixed value
                        $thisTrip->trip_total = floor(($calc_data->start_trip_unit + $calc_data->distance_trip_unit * $distance) - ($promo->value));
                    } else {
                        //promo fixed value
                        $thisTrip->trip_total = floor(($calc_data->start_trip_unit + $calc_data->distance_trip_unit * $distance) - ((($calc_data->start_trip_unit + $calc_data->distance_trip_unit * $distance)) * ($promo->value / 100)));
                    }
                } else {
                    //no promo code set
                    $thisTrip->trip_total = floor($calc_data->start_trip_unit + $calc_data->distance_trip_unit * $distance);
                }
                $driver->trip_total = $thisTrip->trip_total;
                $driver->trip_distance = (string)number_format($distance, 1, '.', '');
                $thisTrip->trip_distance = (string)number_format($distance, 1, '.', '');

                $thisTrip->save();
                $driver->status = 0;
                $driver->user = $thisTrip->user;
                $driver->trip_paths = $thisTrip->trip_paths;

                if ($input->type == 1) { //urgent
                    if ($lang == "en") {
                        $title = 'New order ';
                        $message = 'You have a new order request,please respond';
                    } else {
                        $title = ' طلب جديد ';
                        $message = 'لديك طلب خدمة جديد,الرجاء الإستجابة';
                    }

                    $add = new Notification();
                    $add->title = $title;
                    $add->body = $message;
                    $add->user_id = $driver->id;
                    $add->save();

                    $user = Driver::where("id", $driver->id)->first();
                    Notification::send("$user->token", $title,
                        $message, "", 1,
                        "$driver", NULL, NULL,
                        NULL,NULL,$thisTrip);
                }

            }
            return $thisTrip;
        } else {
            return 'no_drivers';
        }
    }

    public function cancelTrip($input, $lang)
    {
        $trip = Trip::where("id", $input->trip_id)->first();
        $trip->update([
            "status" => 4,
            "canceled_by" => $input->is_captin,
            "cancel_id" => $input->cancel_id,
            "cancel_reason" => $input->cancel_reason,
        ]);

        CaptinInfo::where("user_id", $trip->driver_id)->update(["busy" => 0]);

        $thisTrip = Trip::where("id", $input->trip_id)->first();


        /*DB::table("captin_infos")->where('user_id',$driver_trip->id)
            ->update(['busy'=> 1]);*/

        if ($lang == "en") {
            $title = ' order cancelled ';
            $message = 'order cancelled';
        } else {
            $title = ' طلب اتلغي ';
            $message = ' طلب اتلغي ';
        }

        $add = new Notification();
        $add->title = $title;
        $add->body = $message;
        $add->user_id = $thisTrip->driver_id;
        $add->save();

        $user = User::where("id", $thisTrip->driver_id)->first();
        Notification::send("$user->token",
            $title,
            $message,
            "",
            0,
            $thisTrip,
            null
        );
    }

    public function tripHistory($user_id, $lang)
    {
        global $trips;
        $data = [];
        $allTrips = [];
        //  $trips=Trip::where("user_id",$user_id)
        //         // ->with('driver')
        //         ->with('user')
        //         ->with(["trip_paths" => function($query) use($lang){
        //             $query->select('id','status','address','lat','lng','trip_id');
        //         }])->get();
        if (request()->type == 1) {
            //scheduled trips
            $trips = Trip::orderBy('id', 'desc')
                ->where("user_id", $user_id)
                ->where("type", 'scheduled')
                ->select('')
//                ->with('user')
                ->with(["trip_paths" => function ($query) use ($lang) {
                    $query->select('id', 'status', 'address', 'lat', 'lng', 'trip_id');
                }])
                ->get();
        } else {
            //urgent trips
            $trips = Trip::orderBy('id', 'desc')
                ->where("user_id", $user_id)
//                ->with('user')
//                ->with('driver')
                ->with(["trip_paths" => function ($query) use ($lang) {
                    $query->select('id', 'status', 'address', 'lat', 'lng', 'trip_id');
                }])
                ->get();
        }
        foreach ($trips as $trip) {
            $data['id'] = $trip->id;
            $data['start_lat'] = $trip->start_lat;
            $data['start_lng'] = $trip->start_lng;
            $data['start_address'] = $trip->start_address;
            $size = sizeof($trip->trip_paths);
            $data['end_lat'] = !empty($trip->end_lat) ? $trip->end_lat : $trip->trip_paths[($size) - 1]->lat;
            $data['end_lng'] = !empty($trip->end_lng) ? $trip->end_lng : $trip->trip_paths[($size) - 1]->lng;
            $data['end_address'] = !empty($trip->end_address) ? $trip->end_address : $trip->trip_paths[($size) - 1]->address;
            $data['trip_total'] = isset($trip->trip_total) ? $trip->trip_total : "";
            $data['created_at'] = $trip->created_at;
            $data['rate'] = isset($trip->driver_rate) ? $trip->driver_rate : "";
            $data['comment'] = isset($trip->driver_comment) ? $trip->driver_comment : "";
            $data['currency'] = "currency";

            array_push($allTrips, $data);
//            $trip->car_info = CaptinInfo::where('user_id',$trip->driver_id)
//                ->select('car_image','car_color','color_name','car_num','car_model')
//                ->first();
//            //$trip->driver = User::whereId($trip->driver_id)->select('id','name','phone','lat','lng','image')->first();
//            unset($trip->end_address,$trip->end_lat,$trip->end_lng);
        }
        return $allTrips;

    }

    public function tripDetails($input, $user_id, $lang)
    {
        $trip = Trip::where("user_id", $user_id)
            ->where("id", $input->trip_id)
            ->with('user')
            ->with('driver')
            ->with(["trip_paths" => function ($query) use ($lang) {
                $query->select('id', 'status', 'address', 'lat', 'lng', 'trip_id');
            }])
            ->select('id', 'type', 'user_id', 'driver_id', 'date', 'time', 'user_rate', 'driver_rate', 'trip_total',
                'start_address', 'start_lat', 'start_lng', 'end_lat', 'end_lng', 'end_address', 'created_at')
            ->first();

        $size = sizeof($trip->trip_paths);
        $trip->end_lat = !empty($trip->end_lat) ? $trip->end_lat : $trip->trip_paths[($size) - 1]->lat;
        $trip->end_lng = !empty($trip->end_lng) ? $trip->end_lng : $trip->trip_paths[($size) - 1]->lng;
        $trip->end_address = !empty($trip->end_address) ? $trip->end_address : $trip->trip_paths[($size) - 1]->address;

        if ($trip->driver) {
            $data = [
                'car_image' => $trip->driver->front_car_image,
                'car_color' => $trip->driver->car_color,
                'color_name' => $trip->driver->color_name,
                'car_num' => $trip->driver->car_num,
            ];


            $trip->car_info = $data;
        } else {
            $trip->car_info = null;
        }
        unset($trip->trip_paths);
//        if($trip->driver_id){
//            $trip->car_info = CaptinInfo::where('user_id',$trip->driver_id)
//                ->select('car_image','car_color','color_name','car_num','car_model')
//                ->first();
//
//        }else{
//            $trip->car_info = (object)[];
//            $trip->driver = (object)[];
//        }


        return $trip;
    }

    public function changeStatus($input, $lang)
    {
        Trip::where("id", $input->trip_id)->update([
            "status" => $input->status,
        ]);
    }

    public function chatHistory($input, $user_id, $lang)
    {
        $messages = Message::where("order_id", $input->trip_id)
            ->where('type', 1)
            ->get();
        foreach ($messages as $message) {
            if($message->sender_type == 0){
                $message->sender_image =
                    User::where("id", $message->sender_id)->first()->image;
                $message->receiver_image =
                    Driver::where("id", $message->receiver_id)->first()->image;
            }else{
                $message->sender_image =
                    Driver::where("id", $message->sender_id)->first()->image;
                $message->receiver_image =
                    User::where("id", $message->receiver_id)->first()->image;
            }
//            $message->sender_image =
//                User::where("id", $message->sender_id)->first()->image;
//            $message->receiver_image =
//                User::where("id", $message->receiver_id)->first()->image;
            if ($message->message == null) {
                $image = MessageImage::where('message_id', $message->id)->first();
                $message['image'] = isset($image) ? $image->image : '';
            } else {
                $message['image'] = "";
            }
        }
        return $messages;
    }

    public function addMessage($input, $user_id, $lang)
    {

        $trip = Trip::where("id", $input->trip_id)->first();
        if ($lang == "en") {
            $title = 'New message ';
        } else {
            $title = ' رسالة جديدة ';
        }

        $add = new Message();
        $add->sender_id = $user_id;
        $add->receiver_id = $input->is_captin == 1 ? $trip->user_id : $trip->driver_id;
        $add->sender_type = $input->is_captin == 1 ? 1 : 0;
        $add->receiver_type = $input->is_captin == 1 ? 1 : 0;
        $add->order_id = $input->trip_id;
        $add->type = 1;//trip
        $add->message = isset($input->message) ? $input->message : null;
        $add->save();

        if ($input->image) {
            $message_id = Message::orderBy('id', 'desc')
                ->where('sender_id', $user_id)
                ->orWhere('receiver_id', $user_id)
                ->where('order_id', $input->trip_id)
                ->where('type', 1)
                ->first()->id;
            $add = new MessageImage();
            $add->message_id = $message_id;
            $add->image = $input->image;
            $add->save();
        }

        $message = Message::orderBy("id", "desc")
            ->where("order_id", $input->trip_id)
            ->where('type', 1)
            ->where("sender_id", $user_id)
            ->first();
        if($message->sender_type == 0){
            $message->sender_image =
                User::where("id", $trip->user_id)->first()->image;
            $message->receiver_image =
                Driver::where("id", $trip->driver_id)->first()->image;
        }else{
            $message->sender_image =
                Driver::where("id", $trip->driver_id)->first()->image;
            $message->receiver_image =
                User::where("id", $trip->user_id)->first()->image;
        }



        if ($message->message == null) {
            $image = MessageImage::where('message_id', $message->id)->first();
            $message->image = isset($image) ? $image->image : '';
        } else {
            $message->image = "";
        }

        global $user;


        if ($input->is_captin == 1) {
            $user = User::where("id", $trip->user_id)->first();
        } else {
            $user = Driver::where("id", $trip->driver_id)->first();
        }

        if ($lang == "en") {
            $body = isset($input->message) ? 'New message ' : 'New Image';
        } else {
            $body = isset($input->message) ? 'رسالة جديدة' : 'صورة جديدة';
        }

        Notification::send($user->token, $title,
            $body, 5, 1,
            null, null, NULL,
            $trip->id,null,null,"$message");

//        Notification::send(
//            "$user->token",
//            $title,
//            $body,
//            "",
//            $input->is_captin,
//            null,
//            $message
//        );
        return $message;
    }

    ///// ------- cron job func --------
    public function scheduledTrip()
    {
        $trips = Trip::where('type', 'scheduled')->where('status', 0)
            ->where('date', Carbon::now()->format('Y-m-d'))->where('time', Carbon::now()->format('H:i'))->get();
        if ($trips) {
            foreach ($trips as $trip) {
                $driver_trip =
                    Trip::filterbylatlng($trip->start_lat, $trip->start_lng, 10000, 'users', "$trip->car_level_id");
                if ($driver_trip) {
                    global $thisTrip;
                    foreach ($driver_trip as $driver) {
                        $lang = "ar";
                        $thisTrip = Trip::orderBy("id", "desc")
                            /*->where("driver_id",$driver_trip->id)*/
                            ->where("user_id", $trip->user_id)
                            ->with(["trip_paths" => function ($query) use ($lang) {
                                $query->select('id', 'status', 'address', 'lat', 'lng', 'trip_id');
                            }])->with('user')
                            ->first();
                        $driver->average_rate = Trip::where("driver_id", $driver->id)->avg('user_rate');

                        $driver->trip_id = $thisTrip->id;
                        $driver->start_address = $trip->start_address;
                        $driver->start_lat = $trip->start_lat;
                        $driver->start_lng = $trip->start_lng;
                        /*$driver->end_address = $input->end_address;
                        $driver->end_lat = $input->end_lat;
                        $driver->end_lng = $input->end_lng;*/
                        //calculate distance
                        global $distance;
                        $distance = 0;
                        global $promo;
                        $distance += Trip::calc_distance($thisTrip->trip_paths[0]->lat, $thisTrip->trip_paths[0]->lng,
                            $thisTrip->trip_paths[1]->lat, $thisTrip->trip_paths[1]->lng);
                        if (isset($thisTrip->trip_paths[2]->lat))
                            $distance += Trip::calc_distance($thisTrip->trip_paths[1]->lat, $thisTrip->trip_paths[1]->lng,
                                $thisTrip->trip_paths[2]->lat, $thisTrip->trip_paths[2]->lng);

                        //check validation of promo code
                        if ($trip->promo_id) {
                            //the user entered promo_id in creating trip
                            $promo = $this->checkPromo($trip->promo_id, $trip->car_level_id, $thisTrip->user->country_id, $lang);
                        } else {
                            //the saved promo in users table
                            if (isset($thisTrip->user->promo_code))
                                $promo = $this->checkPromo($thisTrip->user->promo_code, $trip->car_level_id, $thisTrip->user->country_id, $lang);
                        }

                        //
                        $calc_data = CountryCarLevel::where('country_id', $thisTrip->user->country_id)
                            ->where('car_level_id', $trip->car_level_id)->first();

                        if (!empty($promo) && !is_string($promo)) {
                            //promo code set
                            if ($promo->type == 0) {
                                //promo fixed value
                                $thisTrip->trip_total = floor(($calc_data->start_trip_unit + $calc_data->distance_trip_unit * $distance) - ($promo->value));
                            } else {
                                //promo fixed value
                                $thisTrip->trip_total = floor(($calc_data->start_trip_unit + $calc_data->distance_trip_unit * $distance) - ((($calc_data->start_trip_unit + $calc_data->distance_trip_unit * $distance)) * ($promo->value / 100)));
                            }
                        } else {
                            //no promo code set
                            $thisTrip->trip_total = floor($calc_data->start_trip_unit + $calc_data->distance_trip_unit * $distance);
                        }
                        $driver->trip_total = $thisTrip->trip_total;
                        $driver->trip_distance = (string)number_format($distance, 1, '.', '');
                        $thisTrip->trip_distance = (string)number_format($distance, 1, '.', '');

                        $thisTrip->save();
                        $driver->status = 0;
                        $driver->user = $thisTrip->user;
                        $driver->trip_paths = $thisTrip->trip_paths;

                        if ($lang == "en") {
                            $title = 'New order ';
                            $message = 'You have a new order request,please respond';
                        } else {
                            $title = ' طلب جديد ';
                            $message = 'لديك طلب خدمة جديد,الرجاء الإستجابة';
                        }

                        $add = new Notification();
                        $add->title = $title;
                        $add->body = $message;
                        $add->user_id = $driver->id;
                        $add->save();

                        $user = User::where("id", $driver->id)->first();
                        Notification::send("$user->token", $title,
                            $message, "", 1,
                            "$driver", NULL, NULL);
                    }

                    return $thisTrip;
                } else {
                    return 'no_drivers';
                }
            }
        }
    }

}
