<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\CategoryRepositoryInterface;
use App\Models\Offer;
use App\Models\Trip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TripController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $trips = Trip::orderBy('id','desc')
            ->with('user')
            ->with('driver')
            ->get();
        return view('cp.trips.index',[
            'trips'=>$trips,
        ]);

    }

    public function editOfferStatus(Request $request,$id)
    {
        $off=Offer::where("id",$id)->first();
        if($off->active == 1){
            Offer::where("id",$id)
                ->update(["active" => 0 ]);
        }else{
            Offer::where("id",$id)
                ->update(["active" => 1 ]);
        }
        session()->flash('insert_message','تمت العملية بنجاح');
        return back();
    }

    public function checkPaymentSrtatus(Request $request)
    {
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
            Trip::whereId($request->trip_id)->update(['pay_status' => 3]);
        }
        return back();
//End Security API Call
    }

}
