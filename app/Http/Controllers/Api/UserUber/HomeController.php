<?php

namespace App\Http\Controllers\Api\UserUber;


use App\Http\Controllers\Interfaces\UserUber\HomeRepositoryInterface;
use App\Http\Requests\LangRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    protected $homeRepository;
    public function __construct(Request $request,HomeRepositoryInterface $homeRepository)
    {
        $this->homeRepository = $homeRepository;
    }

    public function home(Request $request)
    {
        //$user=checkJWT($request->header('jwt'));
        if($user=checkJWT($request->header('jwt'))) {
        $cars = $this->homeRepository->home($request,$user->country_id,$request->header('lang'));
        //$cars = $this->homeRepository->home($request,3,$request->header('lang'));
        $savedLocations = $this->homeRepository->savedLocations($request,1,$request->header('lang'));
        if($cars)
            return response()->json(msgdata($request, success(),'success',['cars'=>$cars,'savedLocations'=>$savedLocations]));
        else
            return response()->json(msg($request,success(),'no_cars'));
         }
         else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function bankAccounts(Request $request)
    {
        $result = $this->homeRepository->bankAccounts($request,$request->header('lang'));
        if($result)
            return response()->json(msgdata($request, success(),'success',$result));
        else
            return response()->json(msg($request,success(),'no_accounts'));
    }

    public function checkPromoCode(Request $request)
    {
        if($user=checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository
                ->checkPromoCode($request,$user->country_id,$request->header('lang'));
            if($data=="invalid_code"){
                return response()->json(msg($request, failed(),'invalid_code'));
            }elseif($data=="code_expired"){
                return response()->json(msg($request, failed(),'code_expire'));
            }else{
                return response()->json(msgdata($request, success(),'success',$data));
            }
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function countries(Request $request)
    {

        $countries = $this->homeRepository->countries($request->header('lang'));

        return response()->json(msgdata($request,success(),'success',$countries));
    }




}
