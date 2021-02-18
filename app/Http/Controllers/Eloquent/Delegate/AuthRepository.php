<?php

namespace App\Http\Controllers\Eloquent\Delegate;

use App\Http\Controllers\Interfaces\Delegate\AuthRepositoryInterface;
use App\Models\Category;
use App\Models\City;
use App\Models\Delegate;
use App\Models\DelegateDocument;
use App\Models\Order;
use App\Models\ReplacedPoint;
use App\Models\Setting;
use App\Models\User;
use App\Models\Verification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthRepository implements AuthRepositoryInterface
{
    public $model;
    public function __construct(Delegate $model)
    {
        $this->model = $model;
    }

    public function create($input)
    {
        $array = ([
            'role' => $input->role,
            'jwt' => generateJWT(),
            'f_name' => $input->f_name,
            'l_name' => $input->l_name,
            'email' => $input->email,
            'phone' => $input->phone,
            'password' => $input->password,
            'country_id' => $input->city_id,
            'address' => $input->address,
            'lat' => $input->lat,
            'lng' => $input->lng,
            'token' => $input->token,
            'active' => 0,
            'online' => 1,
            'image' => $input->image,

        ]);
        $Delegate = Delegate::create($array);

        $add = new DelegateDocument();
        $add->user_id = $Delegate->id;
        $add->front_car_image = $input->front_car_image;
        $add->back_car_image = $input->back_car_image;
        $add->insurance_image = $input->insurance_image;
        $add->license_image = $input->license_image;
        $add->civil_image = $input->civil_image;
        $add->save();

        $this->sendSMS('delegate', 'activate', $Delegate->phone);

        return $Delegate;
    }

    public function sendSMS($role, $type, $phone)
    {
        $activation_code = generateActivationCode();
        $message = "كود التفعيل الخاص بتطبيق Take-Me هو: ".$activation_code;
        $message = urlencode($message);
        $url = "https://www.hisms.ws/api.php?send_sms&username=966530575553&password=Azoz10887&message=$message&numbers=$phone&sender=ARHC&unicode=e&Rmduplicated=1&return=json";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $decodedData = json_decode($data);
//dd($data);
        Verification::updateOrcreate
        (
            [
                'role' => $role,
                'type' => $type,
                'phone' => $phone,
            ],
            [
                'code' => $activation_code,
                'expire_at' => Carbon::now()->addHour()->toDateTimeString()
            ]
        );
    }

    public function checkIfEmailExists($email)
    {
        return Delegate::whereEmail($email)->first();
    }

    public function checkIfPhoneExists($phone)
    {
        return Delegate::wherePhone($phone)->first();
    }

    public function codeCheck($role,$phone,$code)
    {
        return Verification::whereCode($code)
            ->whereRole('delegate')
            ->wherePhone($phone)
            ->first();
    }

    public function checkJWT($jwt)
    {
        return Delegate::whereJwt($jwt)->select('id', 'password')->first();
    }

    public function checkId($id)
    {
        return Delegate::whereId($id)->first();
    }

    public function activeDelegate($phone)
    {
        $Delegate = $this->checkIfPhoneExists($phone);
        if ($Delegate){
            $Delegate->active = 1;
            $Delegate->save();
            return $Delegate;
        }

    }

    public function delegateData($id,$lang)
    {
        $data = Delegate::whereId($id)
            ->first();
        $data->rate = Order::where('delegate_id', $id)
            ->where('delegate_rate','!=',null)
            ->where('delegate_comment','!=',null)
            ->count();
        return $data;
    }

    public function cities($lang)
    {
        return City::select('id',$lang.'_name as name')->where('active',1)->get();
    }

    public function editeProfile($id,$input,$lang){
        $user = Delegate::where('id', $id)->first();
        //$input->password = Hash::make($input->password);
        //dd($input->all());
        $user->update($input->all());

        if(isset($input->password) && !empty($input->password))
            $user->update(['password' => Hash::make($input->password) ]);

        $user->save();

        $delegateDocument = DelegateDocument::where('user_id',$id)->first();
        if($input->front_car_image){
            $delegateDocument->front_car_image = $input->front_car_image;
            $delegateDocument->front_car_flag = 1;
        }
        if($input->back_car_image){
            $delegateDocument->back_car_image = $input->back_car_image;
            $delegateDocument->back_car_flag = 1;
        }
        if($input->insurance_image){
            $delegateDocument->insurance_image = $input->insurance_image;
            $delegateDocument->insurance_flag = 1;
        }
        if($input->license_image){
            $delegateDocument->license_image = $input->license_image;
            $delegateDocument->license_flag = 1;
        }
        if($input->civil_image){
            $delegateDocument->civil_image = $input->civil_image;
            $delegateDocument->civil_flag = 1;
        }
        $delegateDocument->save();

        return Delegate::where('id', $id)->first();
    }

    public function delegateDocuments($delegate_id){
        $user = DelegateDocument::where('user_id', $delegate_id)->first();

        return $user;
    }

    public function cashPaid($delegate_id){
        $today = date('l', strtotime(Carbon::now()));
        if($today == "Saturday"){
            $firstDayOfWeek = Carbon::now();
        }else{
            $firstDayOfWeek = date('Y-m-d', strtotime("last Saturday"));
        }

         $cashCollectedThisWeek = Order::where('delegate_id',$delegate_id)
            ->where('created_at','>=',$firstDayOfWeek)
            ->sum('total_cost');
        return number_format($cashCollectedThisWeek, 2, '.', '') ;
    }

    public function ordersCount($delegate_id){
        return $ordersCount = Order::where('delegate_id',$delegate_id)
            ->count();
    }

    public function ratesCount($delegate_id){
        return $ratesCount = Order::where('delegate_id',$delegate_id)
            ->where('delegate_rate','!=',null)
            ->where('delegate_comment','!=',null)
            ->count();
    }

    public function calculatePoints($delegate_id){
        $data = [];
        $delegate = Delegate::whereId($delegate_id)
            ->select('points','country_id')->first();
        $points_data = Setting::where('country_id',$delegate->country_id)->first();
        if($points_data){
            $pointsCounts = (int)($delegate->points / $points_data->points);

            $data['points'] = $pointsCounts * $points_data->points;
            $data['money'] = $pointsCounts * $points_data->money;
            return $data;
        }
        return (object)$data;
    }

    public function replacePoints($request,$delegate_id){
        $data = [];
        $delegate = Delegate::whereId($delegate_id)
            ->first();
        $points_data = Setting::where('country_id',$delegate->country_id)->first();
        if($points_data){
            $pointsCounts = (int)($delegate->points / $points_data->points);

            $data['points'] = $pointsCounts * $points_data->points;
            $data['money'] = $pointsCounts * $points_data->money;
            if($data['points'] == (int)$request->points){
                $add = new ReplacedPoint();
                $add->points = $data['points'];
                $add->money = $data['money'];
                $add->user_id = $delegate_id;
                $add->type = 1; //0=>user, 1=>delegate, 2=>driver
                $add->save();

                $delegate->wallet =  $delegate->wallet + $data['money'];
                $delegate->points =  $delegate->points - $data['points'];
                $delegate->save();

                return true;
//                return ReplacedPoint::orderBy('id','desc')
//                    ->where('user_id',$delegate_id)
//                    ->get();
            }else{
                return false;
            }
        }
        return false;
    }


}
