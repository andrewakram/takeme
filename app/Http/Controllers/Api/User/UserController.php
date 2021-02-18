<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Interfaces\User\AuthRepositoryInterface;
use App\Http\Controllers\Interfaces\User\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Worker;
use App\Models\Verification;
use DB;
use Mail;

class UserController extends Controller
{
    protected $userRepository;
    protected $userAuthRepository;
    public function __construct(UserRepositoryInterface $userRepository, AuthRepositoryInterface $authRepository)
    {
        $this->userRepository = $userRepository;
        $this->userAuthRepository = $authRepository;
    }

    public function notification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id'
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->userAuthRepository->checkJWT($request->header('jwt')))
        {
            $notify = $this->userRepository->getNotification($request->user_id)->select($request->header('lang').'_message as message','created_at')->get();
            return response()->json(msgdata($request,success(),'success',$notify));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function chatList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->userAuthRepository->checkJWT($request->header('jwt')))
        {
            $notify = $this->userRepository->getChatList($request);
            return response()->json(msgdata($request,success(),'success',$notify));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function updateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id'
        ]);

        if($validator->fails())
        {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()->first()]);
        }

        if($this->userAuthRepository->checkJWT($request->header('jwt')))
        {
            $user = $this->userRepository->updateUser($request);
            if($user == 'email_exist')
                return response()->json(msg($request, failed(), 'email_exist'));
            if($user == 'phone_exist')
                return response()->json(msg($request, failed(), 'phone_exist'));
            else
                return response()->json(msgdata($request, success(), 'success',$user));
        }
        return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6'
        ]);

        if($validator->fails())
        {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()->first()]);
        }

        if($this->userAuthRepository->checkJWT($request->header('jwt')))
        {
            $user = $this->userRepository->updatePassword($request);

            if($user == false)
                return response()->json(msg($request, failed(), 'old_password'));
            else
                return response()->json(msg($request, success(), 'password_changed'));
        }
        return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function forgetMyPassword(Request $request){

        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'phone' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()->first()]);
        }
        $type= $request->type;
        $phone= $request->phone;
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
                'role' => $type,
                'type' => "reset",
                'phone' => $phone,
            ],
            [
                'code' => $activation_code,
                'expire_at' => Carbon::now()->addHour()->toDateTimeString()
            ]
        );

        /*$this->authRepository->sendSMS('user',$request->type,$request->phone);*/

        return response()->json(msg($request, success(), 'code_sent'));

        /*if($request->type == "user"){
            $data = User::where('phone',$request->phone)->first();
        }elseif($request->type == "worker"){
            $data = Worker::where('phone',$request->phone)->first();
        }
        if($data) {
            $rand_number = rand(1000,9999);
            $verify = Verification::where('phone',$request->phone)
                ->where('role',$request->type)
                ->first();
            $verify->code = $rand_number;
            $verify->save();

            $data1['verification_code'] = $rand_number;
            Mail::send('CheckMail.mail', $data1, function ($message) use ($request,$data) {
                $message->to($data->email)->subject
                ('Activate Code');
                $message->from('newjaz@jaz.my-staff.net', 'JAZ application');
            });

            return response()->json([
                'status' => "success",
                'message' => "Please check your spam mail"
            ]);

        }else{
            return response()->json([
                'status' => "failed",
                'message' => "Error! user not found"
            ]);
        }*/
    }

    public function checkMyCode(Request $request){
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'phone' => 'required|numeric',
            'code' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()->first()]);
        }
        if($request->type == "user"){
            $data = User::join("verifications","verifications.phone","users.phone")
                ->where('verifications.phone',$request->phone)
                ->where('verifications.role',$request->type)
                ->where('verifications.code',$request->code)
                ->orderBy("verifications.id","desc")
                ->first();
        }elseif($request->type == "worker"){
            $data = Worker::join("verifications","verifications.phone","workers.phone")
                ->where('verifications.phone',$request->phone)
                ->where('verifications.role',$request->type)
                ->where('verifications.code',$request->code)
                ->orderBy("verifications.id","desc")
                ->first();
        }
        if($data) {
            return response()->json([
                'status' => "success",
                'message' => "code verification succeeded "
            ]);
        }else{
            return response()->json([
                'status' => "failed",
                'message' => "code verification failed "
            ]);
        }
    }

    public function renewMyPass(Request $request){
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'phone' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()->first()]);
        }

        if($request->type == "user"){
            User::join("verifications","verifications.phone","users.phone")
                ->where('verifications.phone',$request->phone)
                ->where('verifications.role',$request->type)
                ->update([
                    'password' => Hash::make($request->newpassword),
                ]);
            $newData= User::where('phone',$request->phone)
                ->select('id','jwt','name','email','phone','lat','lng','address','image')
                ->get();
        }elseif($request->type == "worker"){
            Worker::join("verifications","verifications.phone","workers.phone")
                ->where('verifications.phone',$request->phone)
                ->where('verifications.role',$request->type)
                ->update([
                    'password' => Hash::make($request->newpassword),
                ]);
            $newData= Worker::where('phone',$request->phone)
                ->select('id','jwt','name','email','phone','lat','lng','address','image')
                ->get();
        }
        if(sizeof($newData) > 0) {
            return response()->json([
                'status' => "success",
                'message' => "New password assigned ",
                'data' => $newData
            ]);
        }else{
            return response()->json([
                'status' => "failed",
                'message' => "New password not assigned "
            ]);
        }
    }

    public function logout(Request $request){
        $jwt=($request->hasHeader('jwt') ? $request->header('jwt') : "");
        if($jwt != ""){
            $result=DB::table("$request->type")
                ->where('id', request("id"))
                ->where('jwt', $jwt)
                ->update([
                    'jwt' => "",
                    'token'=>"",
                ]);
            if($result){
                return response()->json([
                    'status' => "success",
                    'message' => "Logged out successfully",
                ]);
            }
        }else{
            return response()->json([
                'status' => "error",
                'message' => "Error! user not found",
            ]);
        }
    }

    public function updateLocation(Request $request){
        $jwt=($request->hasHeader('jwt') ? $request->header('jwt') : "");
        if($jwt != ""){
            DB::table("$request->type")
                ->where('id', request("id"))
                ->where('jwt', $jwt)
                ->where('role',"!=","company")
                ->update([
                    'lng' => request("lng"),
                    'lat' => request("lat"),
                ]);

                return response()->json([
                    'status' => "success",
                    'message' => "Location updated successfully",
                ]);
            /*}else{
                return response()->json([
                    'status' => "failed",
                    'message' => "Error! user can not update location",
                ]);
            }*/
        }else{
            return response()->json([
                'status' => "failed",
                'message' => "Error! user not found",
            ]);
        }
    }

    public function changeOnlineStatus(Request $request){
        $jwt=($request->hasHeader('jwt') ? $request->header('jwt') : "");
        if($jwt != ""){
            DB::table("workers")
                ->where('jwt', $jwt)
                ->update([
                    'online' => $request->online
                ]);

            return response()->json([
                'status' => "success",
                'message' => "Worker status changed successfully",
            ]);
        }else{
            return response()->json([
                'status' => "failed",
                'message' => "Error! worker not found",
            ]);
        }
    }
    
    public function sendCode(Request $request){
    $phone= $request->phone;
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
                'role' => $request->type,
                'type' => "reset",
                'phone' => $phone,
            ],
            [
                'code' => $activation_code,
                'expire_at' => Carbon::now()->addHour()->toDateTimeString()
            ]
        );
    
    return response()->json(msgdata($request, success(), 'code_sent',["code" => $activation_code]));
}




}
