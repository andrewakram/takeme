<?php

namespace App\Http\Controllers\Api\UserUber;

use App\Http\Controllers\Interfaces\UserUber\AuthRepositoryInterface;
use App\Http\Controllers\Interfaces\UserUber\TripRepositoryInterface;
use App\Models\Notify;
use App\Models\Order;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class TripController extends Controller
{
    protected $tripRepository;
    protected $userAuthRepository;

    public function __construct(TripRepositoryInterface $tripRepository,AuthRepositoryInterface $authRepository)
    {
        $this->tripRepository = $tripRepository;
        $this->authRepository = $authRepository;
    }

    public function calculateTripPrices(Request $request){
        $validator  = Validator::make($request->all(),[
            'start_lat' => 'required',
            'start_lng' => 'required',
            'end_lat' => 'required',
            'end_lng' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['status'=> 401, 'msg'=> $validator->messages()->first()]);
        }
        if($user=checkJWT($request->header('jwt')))
        {
            $cars = $this->tripRepository->calculateTripPrices($request,$user->country_id,$request->header('lang'));
            if($cars)
                return response()->json(msgdata($request, success(),'success',$cars));
            else
                return response()->json(msg($request,success(),'no_cars'));
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function createTrip(Request $request)
    {
        $validator  = Validator::make($request->all(),[
            //'type' => 'required|in:1=urgent,2=scheduled',
            'type' => 'required|in:1,2',
            'car_level_id' => 'required|exists:car_levels,id',
            'start_address' => 'required',
            'start_lat' => 'required',
            'start_lng' => 'required',
//            'end_address' => 'required',
//            'end_lat' => 'required',
//            'end_lng' => 'required',
            'payment' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['status'=> 401, 'msg'=> $validator->messages()->first()]);
        }

        if($user=checkJWT($request->header('jwt')))
        {
            if($request->payment == 2 && $user->wallet == 0) //payment == 2 (from wallet)
                return response()->json(msg($request,failed(),'empty_wallet'));
            $trip = $this->tripRepository
                ->createTrip($request,$user->id,$user->lat,$user->lng,
                    $request->header('lang'),$user->country_id);

            if($trip == 'no_drivers')
                return response()->json(msg($request,failed(),'no_drivers'));
            else
                return response()->json(msgdata($request,success(),'success',$trip));

        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function deleteTrip(Request $request)
    {
        $validator  = Validator::make($request->all(),[
            //'type' => 'required|in:1=urgent,2=scheduled',
            'trip_id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['status'=> 401, 'msg'=> $validator->messages()->first()]);
        }

        if($user=checkJWT($request->header('jwt')))
        {
            $trip = $this->tripRepository->deleteTrip($request,$user->id,$request->header('lang'));
            if($trip)
                return response()->json(msg($request,success(),'success'));
            else
                return response()->json(msg($request,failed(),'failed'));

        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function addLocation(Request $request){
        $validator  = Validator::make($request->all(),[
            'lat'       => 'required',
            'lng'       => 'required',
            'address'   => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['status'=> 401, 'msg'=> $validator->messages()->first()]);
        }

        if($user=checkJWT($request->header('jwt'))){
            $this->tripRepository->addLocation($request,$user->id,$request->header('lang'));
                return response()->json(msg($request,success(),'success'));
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function getLocations(Request $request){
        if($user=checkJWT($request->header('jwt'))){
            $locations=$this->tripRepository->getLocations($user->id,$request->header('lang'));
            return response()->json(msgdata($request,success(),'success',$locations));
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function cancellingReasons(Request $request){
        $validator  = Validator::make($request->all(),[
            'is_captin'       => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['status'=> 401, 'msg'=> $validator->messages()->first()]);
        }
//        if(checkJWT($request->header('jwt'))){
            $reasons=$this->tripRepository->cancellingReasons($request->is_captin,$request->header('lang'));
            return response()->json(msgdata($request,success(),'success',$reasons));
//        }
//        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function cancelTrip(Request $request){
        $validator  = Validator::make($request->all(),[
            'trip_id' => 'required',
            // 'is_captin' => 'required',
            //'cancel_id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['status'=> 401, 'msg'=> $validator->messages()->first()]);
        }
        if(checkJWT($request->header('jwt'))){
            $this->tripRepository->cancelTrip($request,$request->header('lang'));
            return response()->json(msg($request,success(),'success'));
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function tripDetails(Request $request){
        $validator  = Validator::make($request->all(),[
            'trip_id' => 'required|exists:trips,id',
        ]);

        if($validator->fails()){
            return response()->json(['status'=> 401, 'msg'=> $validator->messages()->first()]);
        }
        if($user=checkJWT($request->header('jwt'))){
            $result = $this->tripRepository->tripDetails($request,$user->id,$request->header('lang'));
            return response()->json(msgdata($request,success(),'success',$result));
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function tripHistory(Request $request){

        if($user = checkJWT($request->header('jwt'))){
            $result = $this->tripRepository->tripHistory($user->id,$request->header('lang'));
            return response()->json(msgdata($request,success(),'success',$result));
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function changeStatus(Request $request){
        $validator  = Validator::make($request->all(),[
            'trip_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['status'=> 401, 'msg'=> $validator->messages()->first()]);
        }
        if(checkJWT($request->header('jwt'))){
            $this->tripRepository->changeStatus($request,$request->header('lang'));
            return response()->json(msg($request,success(),'success'));
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function chatHistory(Request $request){
        $validator  = Validator::make($request->all(),[
            'trip_id' => 'required',
            'is_captin' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['status'=> 401, 'msg'=> $validator->messages()->first()]);
        }
        $driver =checkDriverJWT($request->header('jwt'));
        $user =checkJWT($request->header('jwt'));
        if($driver OR $user){
            if($driver){
                $user_id = $driver->id;
            }elseif($user){
                $user_id = $user->id;
            }
            $data=$this->tripRepository->chatHistory($request,$user_id,$request->header('lang'));
            return response()->json(msgdata($request,success(),'success',$data));
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function addMessage(Request $request){
        $validator  = Validator::make($request->all(),[
            'trip_id' => 'required',
            'is_captin' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['status'=> 401, 'msg'=> $validator->messages()->first()]);
        }
        $driver =checkDriverJWT($request->header('jwt'));
        $user =checkJWT($request->header('jwt'));
        if($driver OR $user){
            if($driver){
                $user_id = $driver->id;
            }elseif($user){
                $user_id = $user->id;
            }
            $data=$this->tripRepository->addMessage($request,$user_id,$request->header('lang'));
            return response()->json(msgdata($request,success(),'success',$data));
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    ///// ------- cron job func --------
    public function scheduledTrip()
    {
        $trip = $this->tripRepository->scheduledTrip();

        if($trip == 'no_drivers')
            return 'no_drivers';
        elseif($trip == 'success')
            return 'success';
        else
            return 'false';
    }


}
