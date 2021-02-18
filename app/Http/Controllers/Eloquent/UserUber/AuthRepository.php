<?php

namespace App\Http\Controllers\Eloquent\UserUber;

use App\Models\CaptinInfo;
use App\Http\Controllers\Interfaces\UserUber\AuthRepositoryInterface;
use App\Models\Driver;
use App\Models\PointCountry;
use App\Models\User;
use App\Models\Shop_categorie;
use App\Models\Verification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AuthRepository implements AuthRepositoryInterface {

    public function create($input)
    {
        $jwt = generateJWT();
        $array = array(
            'jwt' => $jwt,
            'name' => $input->name,
            'email' => $input->email,
            'phone' => $input->phone,
            'password' => Hash::make($input->password),
            'lat' => isset($input->lat)?$input->lat:0,
            'lng' => isset($input->lng)?$input->lng:0,
            'is_captin' => $input->is_captin,
            'gender' => 0,
            'dateOfBirth' => $input->is_captin,
            'token' => $input->is_captin,
            'active' => 0,
            'user_code' => 0
        );

        if($user = User::create($array))
        {
            if($input->image) {
                $user->image = $input->image;
            }
            $this->sendSMS( 'activate', $user->phone);
            $user->save();
        }
        return $user->jwt;

    }

    public function captinCompleteRegister($input,$jwt,$lang)
    { // be a captin
        $user=User::where("jwt",$jwt)->first();
        /*dd($user->all());*/
        if($user){
            /************ captin extra data ************/
            $array = array(
                'user_id' => $user->id,
                'car_color' => $input->car_color,
                'car_num' => $input->car_num,
                'car_model' => $input->car_model,
            );
            if($captin = CaptinInfo::create($array))
            {
                if($input->driving_license) {
                    $captin->driving_license = $input->driving_license;
                }
                if($input->id_front) {
                    $captin->id_image_1 = $input->id_front;
                }
                if($input->id_back) {
                    $captin->id_image_2 = $input->id_back;
                }
                if($input->car_license_front) {
                    $captin->car_license_1 = $input->car_license_front;
                }
                if($input->car_license_back) {
                    $captin->car_license_2 = $input->car_license_back;
                }
                if($input->feesh) {
                    $captin->feesh = $input->feesh;
                }

                $captin->save();
            }
            return CaptinInfo::join("users","users.id","captin_infos.user_id")
                ->where("jwt",$jwt)
                ->select("user_id as id","users.jwt","users.name", "users.email", "users.phone",
                    "users.lat","users.lng","users.active","users.is_captin","users.jwt",
                    "users.active","users.suspend","users.image","gender","dateOfBirth",
                    "token", "driving_license","working_hours",
                    "id_image_1 as id_front","id_image_2 as id_back",
                    "car_license_1 as car_license_front","car_license_2 as car_license_back",
                    'feesh','car_color','car_num','car_model','car_level','accept')
                ->first();
        }

    }

    public function updateLocation($input,$driver_id)
    {
        Driver::where('id',$driver_id)
            ->update(['lat'=>$input->lat,'lng'=>$input->lng]);
    }


    public function sendSMS($type,$phone)
    {
        $activation_code = generateActivationCode();

        
        $message = " :كود التفعيل الخاص بك هو".$activation_code;
        $message = urlencode($message);
        $url = "https://www.hisms.ws/api.php?send_sms&username=966504666936&password=Ruh12345&message=$message&numbers=$phone&sender=nearme&unicode=e&Rmduplicated=1&return=json";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $decodedData = json_decode($data);
        


        Verification::updateOrcreate
        (
            [
                'type' => $type,
                'phone' => $phone,
            ],
            [
                'code' => $activation_code,//$activation_code,
                //'expire_at' => Carbon::now()->addHour()->toDateTimeString()
                'expire_at' => Carbon::now()->addDays(30)->toDateTimeString()
            ]
        );
    }

    public function checkIfEmailExist($email)
    {
        $data = User::whereEmail($email)
            ->first();
        if($data){
            $captin = CaptinInfo::where('user_id',$data->id)->first();
            if($captin)
                $data->online = $captin->online;
        }
        return $data;
    }

    public function checkIfPhoneExist($phone)
    {
        $data = User::wherePhone($phone)
            ->first();
        if($data){
            $captin = CaptinInfo::where('user_id',$data->id)->first();
            if($captin)
                $data->online = $captin->online;
        }
        return $data;
    }

    public function checkIfUserExist($id)
    {
        return User::whereId($id)
            ->first();
    }

    public function checkIfEmailExist2($email,$id)
    {
        return User::whereEmail($email)
            ->where("id","!=",$id)
            ->first();
    }

    public function checkIfPhoneExist2($phone,$id)
    {
        $user = User::wherePhone($phone)
            ->where("id","!=",$id)
            ->first();
        return $user;
    }

    /*public function checkJWT($jwt)
    {
        return User::whereJwt($jwt)->select('id','password')->first();
    }*/

    public function checkId($id)
    {
        return User::whereId($id)->first();
    }

    public function codeCheck($code)
    {
         return Verification::whereCode($code)
             ->where('phone',request()->phone)->first();

        return Verification::whereCode($code)
            ->first();
    }

    public function activeUser($phone)
    {
        $user = $this->checkIfPhoneExist($phone);
        $user->active = 1;
        $user->save();
        return $user;
    }

    public function userData($jwt,$is_captin,$lang)
    {
        global $user;
        if($is_captin == 1){
            $check_is_data_completed=User::join('captin_infos','captin_infos.user_id','users.id')
                ->where("jwt",$jwt)->first();
            $user = User::where("jwt",$jwt)->first();
            if($user){
                if($check_is_data_completed){
                    $user->is_captin_data_completed = true;
                }else{
                    $user->is_captin_data_completed = false;
                }
            }

        }else{
            $user = User::where("jwt",$jwt)->first();
        }
        /*if($is_captin == 1){
            $user = User::where("jwt",$jwt)->first();
            $user=CaptinInfo::join("users","users.id","captin_infos.user_id")
                ->where("jwt",$jwt)
                ->select("user_id as id","users.jwt","users.name", "users.email", "users.phone",
                    "users.lat","users.lng","users.active","users.is_captin","users.jwt",
                    "users.active","users.suspend","users.image","gender","dateOfBirth",
                    "token", "driving_license","working_hours",
                    "id_image_1","id_image_2","car_license_1","car_license_2",
                    'feesh','car_color','car_num','car_model','car_level','accept')
                ->first();
        }
        if($is_captin == 0){
            $user = User::where("jwt",$jwt)->first();
        }*/
        if($user){
            $captin = CaptinInfo::where('user_id',$user->id)->first();
            if($captin){
                $user->online = $captin->online;
                $user->is_captin_data_completed = true;
            }

        }
        return $user ;
    }

    public function updateEmail($id,$is_captin,$input,$lang){
        if($input->is_shop == 1) { /*update Captin additional data*/
            User::where('id', $id)
                ->update(['email' => $input->email]);
            /*$user->update(['email' => $input->email]);
            $user2 = User::where('id', $id)->first();
            $user2->update(['email' => $input->email]);*/
        }
        if($input->is_shop == 0) { /*update user data*/
            User::where('id', $id)
                ->update(['email' => $input->email]);
        }
        $user2 = User::where('id', $id)->first();
        return $this->userData($user2->jwt,$is_captin,$lang);
    }

    public function updatePhone($id,$is_shop,$input,$lang){
        if($input->is_shop == 1) { /*update Captin additional data*/
            /*Shop_detail::where('shop_id', $id)
                ->update(['phone' => $input->phone]);*/
            User::where('id', $id)
                ->update(['phone' => $input->phone]);
            /*$user = Shop_detail::where('shop_id', $id)->first();
            $user->update(['phone' => $input->phone]);
            $user2 = User::where('id', $id)->first();
            $user2->update(['phone' => $input->phone]);*/
        }
        if($input->is_shop == 0) { /*update user data*/
            /*$user2 = User::where('id', $id)->first();
            $user2->update(['phone' => $input->phone]);*/
            User::where('id', $id)
                ->update(['phone' => $input->phone]);
        }
        $user2 = User::where('id', $id)->first();
        return $this->userData($user2->jwt,$is_shop,$lang);
    }

    public function editeProfile($id,$input,$lang){
        if($input->is_captin == 1){ /*update Captin additional data*/
            $user = CaptinInfo::where('user_id', $id)->first();

            if( $input->hasFile('id_front') ){
                $user->update([ "id_image_1"  => $input->id_front ]);
            }
            if( $input->hasFile('id_back') ){
                $user->update([ "id_image_2"  => $input->id_back ]);
            }
            if( $input->hasFile('car_license_front') ){
                $user->update([ "car_license_1"  => $input->car_license_front ]);
            }
            if( $input->hasFile('car_license_back') ){
                $user->update([ "car_license_2"  => $input->car_license_back ]);
            }
            if( $input->hasFile('feesh') ){
                $user->update([ "feesh" => $input->feesh ]);
            }
            $user = User::where('id', $id)->first();
            //$user->update($input->all());
            $user->save();
            //

            if( $input->hasFile('image') ){
                $user->update([ "image" => $input->image ]);
            }
            //$user->update($input->all());
            $user->save();
            /*protected $fillable = [
                        'driving_license','working_hours',
                        'id_image_1','id_image_2','car_license_1','car_license_2',
                        'feesh','car_color','user_id',
                        'car_num','car_model','car_level','accept'
                    ];*/

            $user=CaptinInfo::join("users","users.id","captin_infos.user_id")
                ->where("users.id",$id)
                ->select("user_id as id","jwt","name", "email", "phone","image",
                    "active","suspend","token","country_id","lat","lng","is_captin","wallet",
                    "driving_license", "working_hours","id_image_1 as id_front","id_image_2 as id_back","gender","dateOfBirth",
                    "car_license_1 as car_license_front","car_license_2 as car_license_back","feesh","car_color","busy")
                ->first();
            $user->image == NULL ?
                $user->image=asset('default.png')
                :$user->image= asset('/users/'.$user->image);
        } /*End update captin additional data*/
        ////////
        ////////
        if($input->is_captin == 0){ /*update user data*/
            $user = User::where('id', $id)->first();
            //$input->password = Hash::make($input->password);
            $user->update($input->all());
            $user->update(['password' => Hash::make($input->password) ]);
            /*if( $input->hasFile('image') ){
                $user->update([ "image" => $input->image ]);
            }*/
            $user->save();

        } /*End update user data*/
        //
        //return data
        return $user;
    }

    public function getPointOffers($country_id,$lang){
        return PointCountry::where("country_id",$country_id)->get();
    }

    public function convertPoints($id,$input,$lang){
        $user=User::where("id",$id)->first();
        $offer = PointCountry::where("id",$input->offer_id)
            ->where("country_id",$user->country_id)
            ->first();
        if($offer){
            if($offer->points <= $user->points){
                User::where("id",$id)
                    ->update([
                        "points" => $user->points - $offer->points,
                        "wallet" => $user->wallet + $offer->money
                        ]);
                return true;
            }
        }
        return false;
    }

}
