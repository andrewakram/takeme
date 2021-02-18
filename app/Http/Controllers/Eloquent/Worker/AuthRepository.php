<?php

namespace App\Http\Controllers\Eloquent\Worker;

use App\Http\Controllers\Interfaces\Worker\AuthRepositoryInterface;
use App\Models\Category;
use App\Models\City;
use App\Models\Verification;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthRepository implements AuthRepositoryInterface
{
    public $model;
    public function __construct(Worker $model)
    {
        $this->model = $model;
    }

    public function create($input)
    {
        if($input->commercial_register){

                    $array = ([
            'role' => $input->role,
            'jwt' => Str::random(25),
            'name' => $input->name,
            'email' => $input->email,
            'phone' => $input->phone,
            'password' => Hash::make($input->password),
            'city_id' => $input->city_id,
            'address' => $input->address,
            'lat' => $input->lat,
            'lng' => $input->lng,
            'cat_id' => $input->cat_id,
            'id_image' => $input->id_image,
             'commercial_register' => $input->commercial_register,
             'token' => 0,
             'accept' => 0,
             'active' => 1,


        ]);

        }
        else{

            $array = ([
            'role' => $input->role,
            'jwt' => Str::random(25),
            'name' => $input->name,
            'email' => $input->email,
            'phone' => $input->phone,
            'password' => Hash::make($input->password),
            'city_id' => $input->city_id,
            'address' => $input->address,
            'lat' => $input->lat,
            'lng' => $input->lng,
            'cat_id' => $input->cat_id,
            'id_image' => $input->id_image,
            'token' => 0,
            'accept' => 0,
            'active' => 1,

        ]);

        }

        $worker = Worker::create($array);
        if($worker)
        {
            if($input->image)
            {
                $worker->image = $input->image;
            }
            $this->sendSMS('worker','activate',$worker->phone);
            $worker->save();
        }

        $worker = Worker::where("phone",$input->phone)->select('id','jwt','name','email','phone','address','lat','lng','city_id','image','contract','cat_id')
            ->first();
        $worker['city_name'] = City::whereId($worker->city_id)->select($input->header('lang').'_name as name')->first()->name;
        $explode = explode(',',$worker->cat_id);
        $cat = Category::whereIn('id',$explode)->pluck('parent_id')->toArray();
        if(in_array('3',$cat))
        {
            $worker['cat_id'] = 3;
        }

        return $worker;
    }

    public function sendSMS($role, $type, $phone)
    {
        $activation_code = generateActivationCode();
        $message = "كود التفعيل الخاص بجاز هو".$activation_code;
        $message = urlencode($message);
        $url = "https://www.hisms.ws/api.php?send_sms&username=966563244763&password=Aa0563244763&message=$message&numbers=$phone&sender=JazApp&unicode=e&Rmduplicated=1&return=json";
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
        return Worker::whereEmail($email)->select('id')->first();
    }

    public function checkIfPhoneExists($phone)
    {
        return Worker::wherePhone($phone)->select('id','password','token','active','accept')->first();
    }

    public function codeCheck($code)
    {
        return Verification::whereCode($code)->whereRole('worker')->first();
    }

    public function checkJWT($jwt)
    {
        return Worker::whereJwt($jwt)->select('id', 'password')->first();
    }

    public function checkId($id)
    {
        return Worker::whereId($id)->first();
    }

    public function activeWorker($phone)
    {
        $worker = $this->checkIfPhoneExists($phone);
        $worker->active = 1;
        $worker->save();
        return $worker;
    }

    public function workerData($id,$lang)
    {
        $worker = Worker::whereId($id)->select('id','jwt','name','email','phone','address','lat','lng','city_id','image','contract','cat_id','online')
            ->first();
        $worker['city_name'] = City::whereId($worker->city_id)->select($lang.'_name as name')->first()->name;
        $explode = explode(',',$worker->cat_id);
        $cat = Category::whereIn('id',$explode)->pluck('parent_id')->toArray();
        if(in_array('3',$cat))
        {
            $worker['cat_id'] = 3;
        }

        /*$worker['is_selected_car']*/
        return $worker;
    }

    public function cities($lang)
    {
        return City::select('id',$lang.'_name as name')->where('active',1)->get();
    }
}
