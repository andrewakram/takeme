<?php

namespace App\Http\Controllers\Api\UserUber;

use App\Http\Controllers\Interfaces\UserUber\AuthRepositoryInterface;
use App\Http\Requests\CaptinCompleteRegisterReuest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(Request $request, AuthRepositoryInterface $authRepository)
    {
        App::setLocale($request->header('lang'));
        $this->authRepository = $authRepository;
    }

    public function register(RegisterRequest $request)
    {

        $email = $request->email;

        if ($this->authRepository->checkIfEmailExist($email)) {
            return response()->json(msg($request, failed(), 'email_exist'));
        }

        $phone = $request->phone;

        if ($this->authRepository->checkIfPhoneExist($phone)) {
            //dd($request->phone);
            return response()->json(msg($request, failed(), 'phone_exist'));
        }

        $user = $this->authRepository->create($request);

        if ($user) {
            return response()->json(msg($request, success(), 'registered'));
        }
    }

    public function getPointOffers(Request $request)
    {
        if ($data = checkJWT($request->header('jwt'))) {
            if ($data) {
                $data = $this->authRepository
                    ->getPointOffers($request->country_id, $request->header('lang'));
                if ($data) {
                    return response()->json(msgdata($request, success(), 'success', $data));
                }
            }
            return response()->json(msg($request, failed(), 'failed'));
        }

        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function convertPoints(Request $request)
    {
        if ($data = checkJWT($request->header('jwt'))) {
            if ($data) {
                $data = $this->authRepository
                    ->convertPoints($request, $request->id, $request->header('lang'));
                if ($data == true) {
                    $user = $this->authRepository
                        ->userData($request->header('jwt'), 0, $request->header('lang'));
                    return response()->json(msgdata($request, success(), 'success', $user));
                } else {
                    $user = $this->authRepository
                        ->userData($request->header('jwt'), 0, $request->header('lang'));
                    return response()->json(msgdata($request, success(), 'failed', $user));
                }
            }
            return response()->json(msg($request, failed(), 'failed'));
        }

        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function codeSend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            /*'type' => 'required|in:activate,reset',*/
            'phone' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 401, 'msg' => $validator->messages()->first()]);
        }
        if ($this->authRepository->checkIfPhoneExist($request->phone)) {
            $this->authRepository->sendSMS("activate", $request->phone);

            return response()->json(msg($request, success(), 'code_sent'));
        }


        return response()->json(msg($request, failed(), 'failed'));
    }

    public function codeCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 401, 'msg' => $validator->messages()->first()]);
        }

        $check = $this->authRepository->codeCheck($request->code);
        if ($check) {
            if (Carbon::now()->format('Y-m-d H') > Carbon::parse($check->expire_at)->format('Y-m-d H')) {
                return response()->json(msg($request, failed(), 'code_expire'));
            } else {
                /*return response()->json(msg($request, success(), 'success'));*/
                if ($check->type == 'activate') {
                    $this->authRepository->activeUser($check->phone);

                    $user2 = $this->authRepository->checkIfPhoneExist($check->phone);
                    $user = $this->authRepository->userData($user2->jwt, $user2->is_captin, $request->header('lang'));
                    return response()->json(msgdata($request, success(), 'activated', $user));
                }
                $user2 = $this->authRepository->checkIfPhoneExist($check->phone);
                dd($user2);
                $user = $this->authRepository->userData($user2->jwt, $user2->is_shop, $request->header('lang'));
                return response()->json(msgdata($request, success(), 'activated', $user));
            }
        } else {
            return response()->json(msg($request, failed(), 'invalid_code'));
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|',
            //'password' => 'required|min:6',
            'token' => 'required',
            'is_captin' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 401, 'msg' => $validator->messages()->first()]);
        }

        $user = $this->authRepository->checkIfPhoneExist($request->phone);

        if ($user) {
            if ($user->is_captin != (int)$request->is_captin)
                return response()->json(msg($request, failed(), 'invalid_data'));

            if ($request->is_captin == 1) {

                if (Hash::check($request->password, $user->password)) {
                    if ($user->active == 0) {
                        $this->authRepository->sendSMS("activate", $request->phone);
                        return response()->json(msg($request, not_active(), 'not_active'));
                    }
                    $jwt = Str::random(25).time();
                    User::whereId($user->id)->update([
                        'jwt' => $jwt,
                        'token' => $request->token,
                        'lat' => $request->lat,
                        'lng' => $request->lng,
                    ]);
//                    $user->token = $request->token;
//                    $user->lat = $request->lat;
//                    $user->lng = $request->lng;
//                    $user->save();

                    $res = $this->authRepository->userData($jwt,1,request()->header('lang'));
                    return response()->json(msgdata($request, success(), 'logged_in', $res));
                }
            }
            /*if(Hash::check($request->password,$user->password))
            {*/
            if ($request->is_captin == 0) {
                if ($user->active == 0) {
                    $this->authRepository->sendSMS("activate", $request->phone);
                    return response()->json(msg($request, not_active(), 'not_active'));
                }
                $user->jwt = Str::random(25);
                $user->token = $request->token;
                $user->lat = $request->lat;
                $user->lng = $request->lng;
                $user->save();
                return response()->json(msgdata($request, success(), 'logged_in', $user));
            }
            //User::whereId($user->id)->updaet(['token' => $request->token , 'jwt' => Str::random(25)]);
//            $user->token = $request->token;
//            $user->jwt = Str::random(25);
//            $user->save();
            $user = $this->authRepository->userData($user->jwt, $request->is_captin, $request->header('lang'));
            $this->authRepository->sendSMS("activate", $request->phone);
            //dd('jhbnmjb');
            return response()->json(msg($request, failed(), 'invalid_data'));
            /*}
            else return response()->json(msg($request, failed(), 'invalid_data'));*/
        } else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
            /*'user_id' => 'required|exists:users,id'*/
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 401, 'msg' => $validator->messages()->first()]);
        }

        if ($d = checkJWT($request->header('jwt'))) {
            $user = $this->authRepository->checkId($d->id);
            if ($user) {
                $user->password = Hash::make($request->password);
                $user->save();
                return response()->json(msg($request, success(), 'password_changed'));
            }
            return response()->json(msg($request, failed(), 'invalid_data'));
        }


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
            $user = $this->authRepository->userData($request->header('jwt'), $request->is_captin, $request->header('lang'));
            return response()->json(msgdata($request, success(), 'success', $user));
        } else return response()->json(msg($request, failed(), 'invalid_data'));

    }


    //captin
    public function captinCompleteRegister(CaptinCompleteRegisterReuest $request)
    {
        if ($data = checkJWT($request->header('jwt'))) {
            if ($data) {
                $captin_id = $this->authRepository->checkIfUserExist($data->id);
                if ($captin_id) {
                    $captin = $this->authRepository
                        ->captinCompleteRegister($request, $request->header('jwt'), $request->header('lang'));
                    if ($captin) {
                        return response()->json(msgdata($request, success(), 'success', $captin));
                    }
                }
                return response()->json(msg($request, failed(), 'failed'));
            }
            return response()->json(msg($request, failed(), 'email_exist'));
        }

        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function updateLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->fails())
            return response()
                ->json(['status' => 401, 'msg' => $validator->messages()->first]);

        if ($data = checkDriverJWT($request->header('jwt'))) {
            $this->authRepository->updateLocation($request, $data->id);
            return response()->json(msg($request, success(), 'success'));
        } else return response()->json(msg($request, failed(), 'invalid_data'));
    }
}
