<?php

namespace App\Http\Controllers\Api\Worker;

use App\Http\Controllers\Interfaces\Worker\AuthRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|in:worker,company,individual',
            'name' => 'required|max:190',
            'email' => 'required|max:190',
            'phone' => 'required|numeric',
            'password' => 'required|min:6,max:190',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|max:190',
            'lat' => 'required|numeric|max:190',
            'lng' => 'required|numeric|max:190',
            'cat_id' => 'required|max:190',
            'id_image' => 'required',
            'commercial_register' => '',
            'active'=>'1',
            'accept'=>'1',

        ]);

        if($validator->fails()) return response()->json(['status' => 'error', 'msg'=> $validator->messages()->first()]);

        $email = $this->authRepository->checkIfEmailExists($request->email);
        if($email) return response()->json(msg($request, failed(), 'email_exist'));

        $phone = $this->authRepository->checkIfPhoneExists($request->phone);
        if($phone) return response()->json(msg($request, failed(), 'phone_exist'));

        //$this->authRepository->create($request);
        //return response()->json(msg($request, success(), 'registered'));

        $worker=$this->authRepository->create($request);
        $worker = $this->authRepository->workerData($worker->id,$request->header('lang'));

        return response()->json(msgdata($request, success(), 'logged_in', $worker));


    }

    public function codeSend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|in:worker,company,individual',
            'type' => 'required|in:activate,reset',
            'phone' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()->first()]);
        }

        $this->authRepository->sendSMS('worker',$request->type,$request->phone);
        return response()->json(msg($request, success(), 'code_sent'));
    }

    public function codeCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|numeric',
        ]);

        if($validator->fails())
        {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()->first()]);
        }

        $check = $this->authRepository->codeCheck($request->code);
        if($check)
        {
            if(Carbon::now()->format('Y-m-d H') > Carbon::parse($check->expire_at)->format('Y-m-d H'))
                return response()->json(msg($request, failed(), 'code_expire'));
            else
            {
                if($check->type == 'activate')
                {
                    $this->authRepository->activeWorker($check->phone);
                    return response()->json(msg($request, success(), 'activated_waiting'));
                }else{
                    $worker = $this->authRepository->checkIfPhoneExists($check->phone);
                    return response()->json(msgdata($request, success(), 'success',$worker));
                }
            }

        }
        else return response()->json(msg($request, failed(), 'invalid_code'));
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric',
            'password' => 'required',
            'token' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()->first()]);
        }

        $worker = $this->authRepository->checkIfPhoneExists($request->phone);
        if($worker)
        {
            if(Hash::check($request->password, $worker->password))
            {
                if($worker->active == 0) return response()->json(msg($request, not_active(), 'not_active'));
                //if($worker->accept == 0) return response()->json(msg($request, waiting_admin(), 'waiting_admin'));

                $worker->token = $request->token;
                $worker->jwt = Str::random(25);
                $worker->save();
                $worker = $this->authRepository->workerData($worker->id,$request->header('lang'));
                return response()->json(msgdata($request, success(), 'logged_in', $worker));
            }
            else return response()->json(msg($request, failed(), 'invalid_data'));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'password' => 'required|min:6',
            'id' => 'required|exists:workers,id'
        ]);

        if($validator->fails())
        {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()->first()]);
        }

        $worker = $this->authRepository->checkId($request->id);
        if($worker)
        {
            $worker->password = Hash::make($request->password);
            $worker->save();
            return response()->json(msg($request, success(), 'password_changed'));
        }
        return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function cities(Request $request)
    {
        $cities = $this->authRepository->cities($request->header('lang'));
        return response()->json(msgdata($request, success(), 'success',$cities));
    }
}
