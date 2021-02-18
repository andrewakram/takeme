<?php

namespace App\Http\Controllers\Api\Captin;

use App\Http\Controllers\Interfaces\User\UserRepositoryInterface;
//use App\Http\Controllers\Interfaces\Worker\AuthRepositoryInterface;
use App\Http\Controllers\Interfaces\Captin\TripRepositoryInterface;
use App\Models\DriverPayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Trip;

class TripController extends Controller
{
    protected $tripRepository;
    /*protected $workerAuthRepository;
    protected $userRepository;*/
    public function __construct(TripRepositoryInterface $tripRepository)
    {
        $this->tripRepository = $tripRepository;
        /*$this->workerAuthRepository = $authRepository;
        $this->userRepository = $userRepository;*/
    }

    public function changeStatus(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'status' => 'required',
            'trip_id' => 'required|exists:trips,id',
        ]);

        if($validator->fails())
            return response()->json(['status'=> 401,'msg'=>$validator->messages()->first()]);

        if($data=checkDriverJWT($request->header('jwt')) OR $data=checkJWT($request->header('jwt')))
        {
            $trip = $this->tripRepository
                ->changeStatus($request,$data->id,$data->country_id,$request->header('lang'));
            if($trip){
                return response()->json(msgdata($request,success(),'success',$trip));
            }
            return response()->json(msg($request, not_found(), 'trip_go_to_other'));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function calculateTripCost(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'trip_id' => 'required|exists:trips,id',
        ]);

        if($validator->fails())
            return response()->json(['status'=> 401, 'msg'=> $validator->messages()]);

        if(checkDriverJWT($request->header('jwt')))
        {
            $this->tripRepository->calculateTripCost($request);
            return response()->json(msg($request,success(),'success'));
        }
        else return response()->json(msg($request, jwt(), 'invalid_data'));
    }

    public function tripHistory(Request $request,$type,$key){
        //$type = 0=>all, 1=>cash, 2=>credit
        //$key = 0=>weekly, 1=>monthly, 2=>yearly
        if($driver = checkDriverJWT($request->header('jwt'))){
            $result = $this->tripRepository
                ->tripHistory($driver->id,$type,$key,$request->header('lang'),$driver->country_id);
            return response()->json(msgdata($request,success(),'success',$result));
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function rateTrip(Request $request){
        $driver=checkDriverJWT($request->header('jwt'));
        $user=checkJWT($request->header('jwt'));
        if($driver || $user){
            if($driver){
                $is_captin  = 1;
                $user_id  = $driver->id;
            }elseif($user){
                $is_captin  = 0;
                $user_id  = $user->id;
            }
            $this->tripRepository->rateTrip($request,$user_id,$is_captin,$request->header('lang'));
            return response()->json(msg($request,success(),'success'));
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function updateStatus(Request $request){

        if($driver = checkDriverJWT($request->header('jwt'))){
            $this->tripRepository->updateStatus($request,$driver->id,$request->header('lang'));
            return response()->json(msg($request,success(),'success'));
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function collectMoney(Request $request){

        if($driver = checkDriverJWT($request->header('jwt'))){
            $this->tripRepository->collectMoney($request,$driver->id,$request->header('lang'));
            return response()->json(msg($request,success(),'success'));
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function getCredits(Request $request){

        if($driver = checkDriverJWT($request->header('jwt'))){
            $data = $this->tripRepository->getCredits($request,$driver->id,$request->header('lang'));
            return response()->json(msgdata($request,success(),'success',$data));
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

//    public function checkTransactionPayment($TranId,$amount){
    public function checkTransactionPayment(Request $request){
        //
        $validator = Validator::make($request->all(),[
            'trip_id' => 'required|exists:trips,id',
        ]);

        if($validator->fails())
            return response()->json(['status'=> 401,'msg'=>$validator->messages()->first()]);

        $trip = Trip::whereId($request->trip_id)->first();

        $trip_total = $trip->trip_total;
        $TransId = $trip->pay_id;//transid is the payid received in the response of transaction
        $amount="$trip_total";
        //
        $TrackId = $trip->order_num;//order_num of trip
        $currencycode = "SAR";
        $merchant_key = "1a7ead200e753a5a6a73cf1e0169b2f729b4ab9c1bbb95ee74b6d0cf52b5b05a";//secret key
        $password = "near@123";
        $terminalId = "nearme";
        $ip = "184.107.72.172";
        $txn_details1= "".$TrackId."|".$terminalId."|".$password."|".$merchant_key."|".$amount."|".$currencycode."";
        $requestHash1 = hash('sha256', $txn_details1);
        $apifields = array(
            'trackid' => $TrackId,
            'terminalId' => $terminalId,
            'action' => '10',
            'merchantIp' =>$ip,
            'password'=> $password,
            'currency' => $currencycode,
            'transid'=> $TransId,
            'amount' => $amount,
            'udf5'=>"Test5",
            'udf3'=>"Test3",
            'udf4'=>"Test4",
            'udf1'=>"Test1",
            'udf2'=>"Test2",
            'requestHash' => $requestHash1
        );
        $url = "https://payments-dev.urway-tech.com/URWAYPGService/transaction/jsonProcess/JSONrequest";
        $apifields_string = json_encode($apifields);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $apifields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($apifields_string))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
//execute post
        $apiresult = curl_exec($ch);
        //dd($apiresult);
        $urldecodeapi=(json_decode($apiresult,true));
        $inquiryResponsecode=$urldecodeapi['responseCode'];
        $inquirystatus=$urldecodeapi['result'];
        if($inquirystatus == "Successful" && $inquiryResponsecode == "000"){
            Trip::whereId($request->trip_id)->update(['pay_status' => 1]);
        }else{
            Trip::whereId($request->trip_id)->update(['pay_status' => 2]);
        }
//End Security API Call
    }

    public function initiatePayment(Request $request){
        $validator = Validator::make($request->all(),[
            'trip_id' => 'required|exists:trips,id',
        ]);

        if($validator->fails())
            return response()->json(['status'=> 401,'msg'=>$validator->messages()->first()]);

        $trip = Trip::whereId($request->trip_id)->first();
        $trackid = $trip->order_num;
        $trip_total = $trip->trip_total;
//        $trackid = 54154564654654;
        //trackid|Terminalid|password|secret_key|amount|currency_code
        $txn_details= "$trackid|nearme|near@123|1a7ead200e753a5a6a73cf1e0169b2f729b4ab9c1bbb95ee74b6d0cf52b5b05a|$trip_total|SAR";
        $hash=hash('sha256', $txn_details);
        //dd($hash);
        $fields = array(
            'trackid' => $trackid ,
            'terminalId' => "nearme",
            'customerEmail' => 'andrewalbert93501@gmail.com',
            'action' => "1", // action is always 1
            'merchantIp' =>"184.107.72.172",
            'password'=> "near@123",
            'currency' => "SAR",
            'country'=> "SA",
            'amount' => "$trip_total",
            'requestHash' => $hash //generated Hash
        );

        $data = json_encode($fields);

        //dd($data);
        $ch=curl_init('https://payments-dev.urway-tech.com/URWAYPGService/transaction/jsonProcess/JSONrequest');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

//execute post
        $result = curl_exec($ch);
        //dd($result);
//close connection
        curl_close($ch);

        $urldecode=(json_decode($result,true));
        //dd($urldecode);
        if($urldecode['payid'] != NULL) {
            $url = $urldecode['targetUrl'] . "?paymentid=" . $urldecode['payid'];
            Trip::whereId($request->trip_id)->update(['pay_id' => $urldecode['payid']]);
            return redirect($url);
        }else{
            return "<b>Something went wrong!!!!</b>";
        }
    }

    //////////////////////
    ///
    public function shippingWallet(Request $request){
        $validator = Validator::make($request->all(),[
            'value' => 'required',
        ]);

        if($validator->fails())
            return response()->json(['status'=> 401,'msg'=>$validator->messages()->first()]);

//        $trip = Trip::whereId($request->trip_id)->first();
        if($driver = checkDriverJWT($request->header('jwt'))){
            $payment = DriverPayment::create([
               'driver_id' => $driver->id,
               'order_num' => $driver->id . time(),
               'value' => $request->value,
            ]);
            $trackid = $payment->order_num;
            $trip_total = $request->value;
//        $trackid = 54154564654654;
            //trackid|Terminalid|password|secret_key|amount|currency_code
            $txn_details= "$trackid|nearme|near@123|1a7ead200e753a5a6a73cf1e0169b2f729b4ab9c1bbb95ee74b6d0cf52b5b05a|$trip_total|SAR";
            $hash=hash('sha256', $txn_details);
            //dd($hash);
            $fields = array(
                'trackid' => $trackid ,
                'terminalId' => "nearme",
                'customerEmail' => 'andrewalbert93501@gmail.com',
                'action' => "1", // action is always 1
                'merchantIp' =>"184.107.72.172",
                'password'=> "near@123",
                'currency' => "SAR",
                'country'=> "SA",
                'amount' => "$trip_total",
                'requestHash' => $hash //generated Hash
            );

            $data = json_encode($fields);

            //dd($data);
            $ch=curl_init('https://payments-dev.urway-tech.com/URWAYPGService/transaction/jsonProcess/JSONrequest');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data))
            );
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

//execute post
            $result = curl_exec($ch);
            //dd($result);
//close connection
            curl_close($ch);

            $urldecode=(json_decode($result,true));
            //dd($urldecode);
            if($urldecode['payid'] != NULL) {
                $url = $urldecode['targetUrl'] . "?paymentid=" . $urldecode['payid'];
                Trip::whereId($request->trip_id)->update(['pay_id' => $urldecode['payid']]);
                return redirect($url);
            }else{
                return "<b>Something went wrong!!!!</b>";
            }
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));

    }

    //    public function checkTransactionPayment($TranId,$amount){
//    public function checkTransactionshippingWallet(Request $request){
//        //
//        $validator = Validator::make($request->all(),[
//            'value' => 'required',
//        ]);
//
//        if($validator->fails())
//            return response()->json(['status'=> 401,'msg'=>$validator->messages()->first()]);
//
//        if($driver = checkDriverJWT($request->header('jwt'))){
//            $payment = DriverPayment::orderBy('id','desc')
//                ->where('driver_id',$driver->id)
//                ->where('value',$request->value)
//                ->where('pay_status',0)
//                ->first();
//            if($payment){
//                $trip_total = $request->value;
//                $TransId = $payment->pay_id;//transid is the payid received in the response of transaction
//                $amount="$trip_total";
//                //
//                $TrackId = $payment->order_num;//order_num of trip
//                $currencycode = "SAR";
//                $merchant_key = "1a7ead200e753a5a6a73cf1e0169b2f729b4ab9c1bbb95ee74b6d0cf52b5b05a";//secret key
//                $password = "near@123";
//                $terminalId = "nearme";
//                $ip = "184.107.72.172";
//                $txn_details1= "".$TrackId."|".$terminalId."|".$password."|".$merchant_key."|".$amount."|".$currencycode."";
//                $requestHash1 = hash('sha256', $txn_details1);
//                $apifields = array(
//                    'trackid' => $TrackId,
//                    'terminalId' => $terminalId,
//                    'action' => '10',
//                    'merchantIp' =>$ip,
//                    'password'=> $password,
//                    'currency' => $currencycode,
//                    'transid'=> $TransId,
//                    'amount' => $amount,
//                    'udf5'=>"Test5",
//                    'udf3'=>"Test3",
//                    'udf4'=>"Test4",
//                    'udf1'=>"Test1",
//                    'udf2'=>"Test2",
//                    'requestHash' => $requestHash1
//                );
//                $url = "https://payments-dev.urway-tech.com/URWAYPGService/transaction/jsonProcess/JSONrequest";
//                $apifields_string = json_encode($apifields);
//                $ch = curl_init($url);
//                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//                curl_setopt($ch, CURLOPT_POSTFIELDS, $apifields_string);
//                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//                        'Content-Type: application/json',
//                        'Content-Length: ' . strlen($apifields_string))
//                );
//                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
//                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
////execute post
//                $apiresult = curl_exec($ch);
//                //dd($apiresult);
//                $urldecodeapi=(json_decode($apiresult,true));
//                $inquiryResponsecode=$urldecodeapi['responseCode'];
//                $inquirystatus=$urldecodeapi['result'];
//                if($inquirystatus == "Successful" && $inquiryResponsecode == "000"){
//                    $user = User::whereId($driver->id)->first();
//                    User::whereId($driver->id)->update(['wallet' => $user->wallet + $request->value]);
//                    DriverPayment::orderBy('id','desc')
//                        ->where('driver_id',$driver->id)
//                        ->where('value',$request->value)
//                        ->where('pay_status',0)
//                        ->update(['pay_status' => 1]);
//                    return response()->json(msg($request,success(),'payment_success'));
//                }else{
//                    DriverPayment::orderBy('id','desc')
//                        ->where('driver_id',$driver->id)
//                        ->where('value',$request->value)
//                        ->where('pay_status',0)
//                        ->update(['pay_status' => 2]);
//                    return response()->json(msg($request,failed(),'payment_failed'));
//                }
////End Security API Call
//            }
//
//
//        }
//        else return response()->json(msg($request,not_authoize(),'invalid_data'));
//
//    }

}
