<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Interfaces\User\AuthRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected $authRepository;

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

        if ($this->authRepository->checkIfPhoneExist($request->phone)) {
            return response()->json(msg($request, success(), 'phone_checked'));
        }
        return response()->json(msg($request, failed(), 'phone_not_exist'));

    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|numeric',
            'password' => 'required|min:6',
//            'address' => 'required',
//            'lat' => 'required',
//            'lng' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => failed(), 'msg' => $validator->messages()->first()]);
        }

//        $email = $request->email;
//
//        if($this->authRepository->checkIfEmailExist($email))
//        {
//            return response()->json(msg($request, failed(), 'email_exist'));
//        }

        if ($this->authRepository->checkIfPhoneExist($request->phone)) {
            return response()->json(msg($request, failed(), 'phone_exist'));
        }

        $user = $this->authRepository->create($request);

        if ($user) {
            //$user = $this->authRepository->userData($user);
            return response()->json(msg($request, success(), 'register_success'));
            //return response()->json(msg($request, success(), 'register_success'));
        }
    }

    public function codeSend(Request $request)
    {
        $validator = Validator::make($request->all(), [
//            'role' => 'required|in:user,company',
//            'type' => 'required|in:activate,reset',
            'phone' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()->first()]);
        }

        if ($this->authRepository->checkIfPhoneExist($request->phone)) {
            $this->authRepository->sendSMS('user', "reset", "$request->phone");

            return response()->json(msg($request, success(), 'code_sent'));
        }

        return response()->json(msg($request, failed(), 'phone_not_exist'));
    }

    public function codeCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => failed(), 'msg' => $validator->messages()->first()]);
        }

        $check = $this->authRepository->codeCheck('user', $request->phone, $request->code);
        if ($check) {
            if (Carbon::now()->format('Y-m-d H') > Carbon::parse($check->expire_at)->format('Y-m-d H')) {
                return response()->json(msg($request, failed(), 'code_expire'));
            } else {
                $user = $this->authRepository->checkIfPhoneExist($check->phone);
                if ($check->type == 'activate') {
                    $this->authRepository->activeUser($check->phone);
                    return response()->json(msgdata($request, success(), 'activated', $user));
                } else {
                    return response()->json(msgdata($request, success(), 'activated', $user));
                }
            }
        } else {
            return response()->json(msg($request, failed(), 'invalid_code'));
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric',
            'password' => 'required',
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => failed(), 'msg' => $validator->messages()->first()]);
        }

        $user = $this->authRepository->checkIfPhoneExist($request->phone);
        if ($user) {
            if (Auth::attempt([
                'phone' => $request->phone,
                'password' => $request->password
            ])) {
                if ($user->active == 0) {
                    //$this->authRepository->sendSMS("user","activate", "$request->phone");
                    return response()->json(msg($request, not_active(), 'not_active'));
                }
                $user->token = $request->token;
                $user->jwt = generateJWT();
                $user->save();
                return response()->json(msgdata($request, success(), 'logged_in', $user));
            }
            else return response()->json(msg($request, failed(), 'invalid_data'));
        } else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => failed(), 'msg' => $validator->messages()->first()]);
        }

        $user = $this->authRepository->checkId($request->user_id);
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json(msg($request, success(), 'password_changed'));
        }
        return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function updateProfile(Request $request)
    {
        if ($d = checkJWT($request->header('jwt'))) {

            /////
            $user = $this->authRepository->editeProfile($d->id, $request, $request->header('lang'));
            if ($user) {
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
            $user = $this->authRepository->userData($request->header('jwt'));
            return response()->json(msgdata($request, success(), 'success', $user));
        } else return response()->json(msg($request, failed(), 'invalid_data'));

    }
}
