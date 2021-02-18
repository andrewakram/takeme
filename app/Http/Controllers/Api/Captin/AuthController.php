<?php

namespace App\Http\Controllers\Api\Captin;

use App\Http\Controllers\Interfaces\Captin\AuthRepositoryInterface;
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

    public function phoneCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => failed(), 'msg' => $validator->messages()->first()]);
        }

        if ($this->authRepository->checkIfPhoneExists($request->phone)) {
            return response()->json(msg($request, success(), 'phone_checked'));
        }
        return response()->json(msg($request, not_found(), 'phone_not_exist'));

    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required|max:190',
            'l_name' => 'required|max:190',
            'phone' => 'required|numeric',
            'email' => 'required',

            'lat' => '',
            'lng' => '',

            'token' => '',

            'bank_name' => 'required',
            'bank_account_name' => 'required',
            'bank_account_num' => 'required',
            'car_color' => 'required',
            'color_name' => 'required',
            'car_num' => 'required',
            'car_text' => 'required',
            'car_level' => 'required',
            'national_id' => 'required',
            'national_id_type' => 'required',
            'image'=>'',
            'front_car_image'=>'required',
            'back_car_image'=>'required',
            'insurance_image'=>'required',
            'license_image'=>'required',
            'civil_image'=>'required',

        ]);

        if($validator->fails())
            return response()->json(['status' => 'error', 'msg'=> $validator->messages()->first()]);

        $email = $this->authRepository->checkIfEmailExists($request->email);
        if($email) return response()->json(msg($request, failed(), 'email_exist'));

        $phone = $this->authRepository->checkIfPhoneExists($request->phone);
        if($phone) return response()->json(msg($request, failed(), 'phone_exist'));

        //$this->authRepository->create($request);
        //return response()->json(msg($request, success(), 'registered'));

        $worker=$this->authRepository->create($request);

        return response()->json(msgdata($request, success(), 'logged_in', $worker));


    }

    public function codeSend(Request $request)
    {
        $validator = Validator::make($request->all(), [
//            'role' => 'required|in:worker,company,individual',
//            'type' => 'required|in:activate,reset',
            'phone' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()]);
        }
        if ($this->authRepository->checkIfPhoneExists($request->phone)) {
            $this->authRepository->sendSMS('driver', 'reset', $request->phone);
            return response()->json(msg($request, success(), 'code_sent'));
        }
        return response()->json(msg($request, failed(), 'phone_not_exist'));
    }

    public function codeCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|numeric',
            'phone' => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()]);
        }

        $user = $this->authRepository->checkIfPhoneExists($request->phone);
        if ($user) {
            $check = $this->authRepository->codeCheck('delegate', $request->phone, $request->code);
            if ($check) {
                if (Carbon::now()->format('Y-m-d H') > Carbon::parse($check->expire_at)->format('Y-m-d H'))
                    return response()->json(msg($request, failed(), 'code_expire'));
                else {
                    if ($check->type == 'activate') {
                        $this->authRepository->activeDriver($check->phone);
                        return response()->json(msgdata($request, success(), 'activated', $user));
//                        if($user->accept == 1)
//                            return response()->json(msg($request, success(), 'activated'));
//                        return response()->json(msg($request, failed(), 'activated_waiting'));
                    } else {
                        $worker = $this->authRepository->checkIfPhoneExists($check->phone);
                        return response()->json(msgdata($request, success(), 'success', $worker));
                    }
                }

            } else return response()->json(msg($request, failed(), 'invalid_code'));
        }
        return response()->json(msg($request, not_found(), 'invalid_code'));
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric',
            'password' => 'required|min:6',
            'token' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()]);
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
            return response()->json(['status' => 'error', 'msg' => $validator->messages()]);
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

    public function addBankTransfer(Request $request){
        $validator  = Validator::make($request->all(),[
            // 'bank_name' => 'required',
            // 'transfer_no' => 'required',
            // 'transfer_value' => 'required',
            'image' => 'required',
            'bank_account_id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['status'=> 401, 'msg'=> $validator->messages()->first()]);
        }
        if($user=checkDriverJWT($request->header('jwt'))){
            $this->authRepository->addBankTransfer($request,$user->id);
            return response()->json(msg($request,success(),'success'));
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function bankingTransfers(Request $request){
        if($user=checkDriverJWT($request->header('jwt'))){
            $data=$this->authRepository->bankingTransfers($user->id);
            return response()->json(msgdata($request,success(),'success',$data));
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function updateProfile(Request $request)
    {
        if ($d = checkDriverJWT($request->header('jwt'))) {

            if ($d) {
                $user = $this->authRepository->editeProfile($d->id, $request, $request->header('lang'));
                return response()->json(msgdata($request, success(), 'success', $user));
            }
            /////
            /*if($request->email){
                if($this->authRepository->checkIfEmailExist2($request->email,$d->id))
                {
                    return response()->json(msg($request, failed(), 'email_exist'));
                }else{
                    $this->authRepository->updateEmail($d->id,$d->is_captin,$request,$request->header('lang'));
                }
            }*/
            /////
            /*if($request->phone){
                if($this->authRepository->checkIfPhoneExist2($request->phone,$d->id))
                {
                    return response()->json(msg($request, failed(), 'phone_exist'));
                }else{
                    $this->authRepository->updatePhone($d->id,$d->is_captin,$request,$request->header('lang'));
                }
            }*/
//            $user = $this->authRepository->delegateData($request->header('jwt'));
//            return response()->json(msgdata($request, success(), 'success', $user));
        } else return response()->json(msg($request, failed(), 'invalid_data'));

    }

    public function getMyCarLevels(Request $request){
        if($user=checkDriverJWT($request->header('jwt'))){
            $data=$this->authRepository->getMyCarLevels($user->id,$user->country_id);
            return response()->json(msgdata($request,success(),'success',$data));
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function updateMyCarLevels(Request $request){
        if($user=checkDriverJWT($request->header('jwt'))){
            $data=$this->authRepository->updateMyCarLevels($user->id,$user->country_id,$request);
            return response()->json(msgdata($request,success(),'success',$data));
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function driverDocuments(Request $request)
    {
        if ($d = checkDriverJWT($request->header('jwt'))) {

            if ($d) {
                $data = $this->authRepository->driverDocuments($d->id);
                return response()->json(msgdata($request, success(), 'success', $data));
            }
        } else return response()->json(msg($request, failed(), 'invalid_data'));
    }

}
