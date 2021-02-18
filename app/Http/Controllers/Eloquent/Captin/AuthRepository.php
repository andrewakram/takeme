<?php

namespace App\Http\Controllers\Eloquent\Captin;

use App\Http\Controllers\Interfaces\Captin\AuthRepositoryInterface;
use App\Models\BankingTransfer;
//use App\Models\Category;
//use App\Models\City;
use App\Models\CarLevel;
use App\Models\CountryCarLevel;
use App\Models\DelegateDocument;
use App\Models\Driver;
use App\Models\DriverCarLevel;
use App\Models\DriverDocument;
use App\Models\Verification;
//use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthRepository implements AuthRepositoryInterface
{
    public $model;
    public function __construct()
    {
        //$this->model = $model;
    }

    public function create($input)
    {
        $array = ([
            'f_name' => $input->f_name,
            'l_name' => $input->l_name,
            'phone' => $input->phone,
            'email' => $input->email,

            'password' => $input->password,
            'city_id' => $input->city_id,
            'lat' => $input->lat,
            'lng' => $input->lng,

            'token' => $input->token,

            'bank_name' => $input->bank_name,
            'bank_account_name' => $input->bank_account_name,
            'bank_account_num' => $input->bank_account_num,
            'car_num' => $input->car_num,
            'car_text' => $input->car_text,
            'car_level' => $input->car_level,
            'car_color' => $input->car_color,
            'color_name' => $input->color_name,
            'national_id' => $input->national_id,
            'national_id_type' => $input->national_id_type,
            'image'=>isset($input->image) ? $input->image :null,
//            'front_car_image'=>isset($input->front_car_image) ? $input->front_car_image :null,
//            'back_car_image'=>isset($input->back_car_image) ? $input->back_car_image:null,
//            'insurance_image'=>isset($input->insurance_image) ? $input->insurance_image:null,
//            'license_image'=>isset($input->license_image) ? $input->license_image:null,
//            'civil_image'=>isset($input->civil_image) ? $input->civil_image:null,
            'jwt' => generateJWT(),
            'active' => 0,
            'online' => 1,
        ]);

        $driver = Driver::create($array);

        $add = new DriverDocument();
        $add->user_id = $driver->id;
        $add->front_car_image = $input->front_car_image;
        $add->back_car_image = $input->back_car_image;
        $add->insurance_image = $input->insurance_image;
        $add->license_image = $input->license_image;
        $add->civil_image = $input->civil_image;
        $add->save();

        if($driver)
        {
            $this->sendSMS('driver','activate',$driver->phone);
            $driver->save();
        }
        return $driver;
//
//        $worker = Worker::where("phone",$input->phone)->select('id','jwt','name','email','phone','address','lat','lng','city_id','image','contract','cat_id')
//            ->first();
//        $worker['city_name'] = City::whereId($worker->city_id)->select($input->header('lang').'_name as name')->first()->name;
//        $explode = explode(',',$worker->cat_id);
//        $cat = Category::whereIn('id',$explode)->pluck('parent_id')->toArray();
//        if(in_array('3',$cat))
//        {
//            $worker['cat_id'] = 3;
//        }
//
//        return $worker;
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
        return Driver::whereEmail($email)->first();
    }

    public function checkIfPhoneExists($phone)
    {
        return Driver::wherePhone($phone)->first();
    }

    public function codeCheck($role,$phone,$code)
    {
        return Verification::whereCode($code)
            ->whereRole('driver')
            ->wherePhone($phone)
            ->first();
    }

    public function checkJWT($jwt)
    {
        return Driver::whereJwt($jwt)->first();
    }

    public function checkId($id)
    {
        return Driver::whereId($id)->first();
    }

    public function activeDriver($phone)
    {
        $worker = $this->checkIfPhoneExists($phone);
        $worker->active = 1;
        $worker->save();
        return $worker;
    }

    public function workerData($id,$lang)
    {
        $worker = Driver::whereId($id)
            ->first();

        return $worker;
    }

    public function cities($lang)
    {
        return City::select('id',$lang.'_name as name')->get();
    }
    ////////////////////
    ///

    public function addBankTransfer($input,$user_id){
        $add                    = new BankingTransfer();
        $add->bank_name         = $input->bank_name;
        $add->transfer_no       = $input->transfer_no;
        $add->transfer_value    = $input->transfer_value;
        $add->image             = $input->image;
        $add->user_id           = $user_id;
        $add->bank_account_id   = $input->bank_account_id;
        $add->save();
    }

    public function bankingTransfers($user_id){
        $results=BankingTransfer::orderBy('id','desc')
            ->join('bank_accounts','bank_accounts.id','banking_transfers.bank_account_id')
            ->where('user_id',$user_id)
            ->select('banking_transfers.id','banking_transfers.transfer_no','banking_transfers.transfer_value',
                'banking_transfers.image as transfer_image',
                'banking_transfers.user_id',
                'banking_transfers.bank_account_id',
                'banking_transfers.created_at',
                'bank_accounts.bank_name','bank_accounts.account_no','bank_accounts.image as bank_image'
            )
            ->get();
        foreach($results as $result){
            if($result->bank_image != null)
            {
                $result->bank_image = asset('/bank_accounts/'.$result->bank_image);
            }else{
                $result->bank_image = asset('/default.png');
            }
        }
        return $results;
    }

    public function editeProfile($id,$input,$lang){
        $user = Driver::where('id', $id)->first();
        //$input->password = Hash::make($input->password);
        //dd($input->all());
        $user->update($input->all());

        if(isset($input->password) && !empty($input->password))
            $user->update(['password' => Hash::make($input->password) ]);

        $user->save();

        $delegateDocument = DriverDocument::where('user_id',$id)->first();
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

        return Driver::where('id', $id)->first();
    }

    public function getMyCarLevels($user_id,$user_country_id){
        $driver_car_levels = DriverCarLevel::where('driver_id',$user_id)
            ->pluck('car_level_id')
            ->toArray();
        $cars=CarLevel::join("country_car_levels","car_levels.id","country_car_levels.car_level_id")
            ->where("country_car_levels.country_id",$user_country_id)
            ->select("car_levels.id","car_levels.name","car_levels.image","car_levels.description",
                "start_trip_unit", "distance_trip_unit")->get();

        foreach ($cars as $car){
            if(in_array($car->id, $driver_car_levels)){
                $car->is_selected = 1;
            }else{
                $car->is_selected = 0;
            }
        }
        return $cars;
    }

    public function updateMyCarLevels($user_id,$user_country_id,$request){
        DriverCarLevel::where('driver_id',$user_id)->delete();
        foreach ($request->car_levels as $car_level){
            DriverCarLevel::create([
                'driver_id' => $user_id,
                'car_level_id' => $car_level,
            ]);
        }

        $cars=CarLevel::join("country_car_levels","car_levels.id","country_car_levels.car_level_id")
            ->where("country_car_levels.country_id",$user_country_id)
            ->select("car_levels.id","car_levels.name","car_levels.image","car_levels.description",
                "start_trip_unit", "distance_trip_unit")->get();

        foreach ($cars as $car){
            if(in_array($car->id, $request->car_levels)){
                $car->is_selected = 1;
            }else{
                $car->is_selected = 0;
            }
        }
        return $cars;
    }

    public function driverDocuments($driver_id){
        $user = DriverDocument::where('user_id', $driver_id)->first();

        return $user;
    }

}
