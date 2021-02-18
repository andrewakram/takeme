<?php

namespace App\Http\Controllers\Api\Delegate;

use App\Http\Controllers\Interfaces\Delegate\AuthRepositoryInterface;
use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderStatus;
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
            'email' => 'required|max:190',
            'phone' => 'required|',
            'password' => 'required|min:6,max:190',
            'city_id' => 'required|exists:cities,id',
            'image' => 'required',
            'front_car_image' => 'required',
            'back_car_image' => 'required',
            'insurance_image' => 'required',
            'license_image' => 'required',
            'civil_image' => 'required',
//            'address' => 'required|max:190',
//            'lat' => 'required|numeric|max:190',
//            'lng' => 'required|numeric|max:190',
//            'cat_id' => 'required|max:190',
//            'id_image' => 'required',
//            'commercial_register' => '',
        ]);

        if ($validator->fails())
            return response()->json(['status' => failed(), 'msg' => $validator->messages()->first()]);


        $phone = $this->authRepository->checkIfPhoneExists($request->phone);
        if ($phone) return response()->json(msg($request, failed(), 'phone_exist'));

        //$this->authRepository->create($request);
        //return response()->json(msg($request, success(), 'registered'));

        $delegate = $this->authRepository->create($request);
        $delegate = $this->authRepository->delegateData($delegate->id, $request->header('lang'));

        return response()->json(msgdata($request, success(), 'logged_in', $delegate));


    }

    public function codeSend(Request $request)
    {
        $validator = Validator::make($request->all(), [
//            'role' => 'required|in:worker,company,individual',
//            'type' => 'required|in:activate,reset',
            'phone' => 'required|',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => failed(), 'msg' => $validator->messages()->first()]);
        }
        if ($this->authRepository->checkIfPhoneExists($request->phone)) {
            $this->authRepository->sendSMS('delegate', 'reset', $request->phone);
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

        if ($validator->fails()) {
            return response()->json(['status' => failed(), 'msg' => $validator->messages()->first()]);
        }

        $user = $this->authRepository->checkIfPhoneExists($request->phone);
        if ($user) {
            $check = $this->authRepository->codeCheck('delegate', $request->phone, $request->code);
            if ($check) {
                if (Carbon::now()->format('Y-m-d H') > Carbon::parse($check->expire_at)->format('Y-m-d H'))
                    return response()->json(msg($request, failed(), 'code_expire'));
                else {
                    if ($check->type == 'activate') {
                        $this->authRepository->activeDelegate($check->phone);
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
            'password' => 'required',
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => failed(), 'msg' => $validator->messages()->first()]);
        }

        $worker = $this->authRepository->checkIfPhoneExists($request->phone);
        if ($worker) {
            if (Hash::check($request->password, $worker->password)) {
                if ($worker->active == 0) return response()->json(msg($request, not_active(), 'not_active'));
//                if($worker->accept == 0) return response()->json(msg($request, failed(), 'activated_waiting'));

                $worker->token = $request->token;
                $worker->jwt = Str::random(25);
                $worker->save();
                $worker = $this->authRepository->delegateData($worker->id, $request->header('lang'));
                return response()->json(msgdata($request, success(), 'logged_in', $worker));
            } else return response()->json(msg($request, failed(), 'invalid_data'));
        } else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
            'id' => 'required|exists:workers,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => failed(), 'msg' => $validator->messages()->first()]);
        }

        $worker = $this->authRepository->checkId($request->id);
        if ($worker) {
            $worker->password = Hash::make($request->password);
            $worker->save();
            return response()->json(msg($request, success(), 'password_changed'));
        }
        return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function cities(Request $request)
    {
        $cities = $this->authRepository->cities($request->header('lang'));
        return response()->json(msgdata($request, success(), 'success', $cities));
    }

    public function updateProfile(Request $request)
    {
        if ($d = checkDelegateJWT($request->header('jwt'))) {

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

    public function delegateDocuments(Request $request)
    {
        if ($d = checkDelegateJWT($request->header('jwt'))) {

            if ($d) {
                $data = $this->authRepository->delegateDocuments($d->id);
                return response()->json(msgdata($request, success(), 'success', $data));
            }
        } else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function profileData(Request $request)
    {
        if ($d = checkDelegateJWT($request->header('jwt'))) {

            if ($d) {
                $userData = $delegate = $this->authRepository->delegateData($d->id, $request->header('lang'));
                $cashPaid = $this->authRepository->cashPaid($d->id);
                $ordersCount = $this->authRepository->ordersCount($d->id);
                $ratesCount = $this->authRepository->ratesCount($d->id);
                $calculatedPoints = $this->authRepository->calculatePoints($d->id);

                //
                $order_ids = Order::where('delegate_id',$d->id)->pluck('id');
                $finished_orders = OrderStatus::whereIn('order_id',$order_ids)
                    ->where('finished','!=',NULL)
                    ->where('cancelled',NULL)
                    ->pluck('order_id');
                $finished_orders_offers = Order::whereIn('id',$finished_orders)->with('offer')->get();
                $total_values_of_finished_orders = 0;
                foreach ($finished_orders_offers as $finished_orders_offer){
                    if($finished_orders_offer->offer)
                        $total_values_of_finished_orders = $total_values_of_finished_orders + $finished_orders_offer->offer->offer;
                }
                //dd($total_values_of_finished_orders);
                $app_percent = Admin::where('email','admin@admin.com')->select('app_percent')->first()->app_percent;
                $app_fees = $total_values_of_finished_orders * ($app_percent /100);
//dd($order_ids);
                //$delivery_fees =
                return response()
                    ->json(msgdata($request, success(), 'success',
                    [
                        'user_data'          => $userData,
                        'delivery_fees'      => number_format(($total_values_of_finished_orders - $app_fees), 1, '.', ''),
                        'cash_paid'          => $cashPaid,
                        'orders_count'       => $ordersCount,
                        'rates_count'        => $ratesCount,
                        'calculated_points'  => $calculatedPoints,
                    ]));
            }
        } else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function replacePoints(Request $request)
    {
        if ($d = checkDelegateJWT($request->header('jwt'))) {

            if ($d) {
                $data = $this->authRepository->replacePoints($request,$d->id);
                if($data)
                    return response()->json(msg($request, success(), 'success'));
                return response()->json(msgdata($request, failed(), 'points_invalid',[]));
            }
        } else return response()->json(msg($request, failed(), 'invalid_data'));
    }
}
