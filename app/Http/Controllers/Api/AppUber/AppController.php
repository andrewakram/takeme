<?php

namespace App\Http\Controllers\Api\AppUber;

use App\Http\Controllers\Interfaces\AppUber\AppRepositoryInterface;
use App\Http\Controllers\Interfaces\User\AuthRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AppController extends Controller
{
    protected $appRepository;
    protected $userAuthRepository;
    public function __construct(AppRepositoryInterface $appRepository,AuthRepositoryInterface $userAuthRepository)
    {
        $this->appRepository = $appRepository;
        $this->userAuthRepository = $userAuthRepository;
    }

    public function complainSuggest(Request $request)
    {
//        $validator = Validator::make($request->all(),[
//            'title' => 'required|max:190',
//            'description' => 'required'
//        ]);
//
//        if($validator->fails())
//        {
//            return response()->json(['status' => 'error', 'msg' => $validator->messages()]);
//        }

        if($user=checkJWT($request->header('jwt')) OR
            $driver=checkDriverJWT($request->header('jwt')) OR
            $delegate=checkDelegateJWT($request->header('jwt')))
        {
            //type >> 	0=>user, 1=>delegate, 2=>driver
            if(isset($user)){
                $d =$user->id;
                $type = 0;
            }elseif(isset($delegate)){
                $d = $delegate->id;
                $type = 1;
            }else{
                $d = $driver->id;
                $type = 2;
            }
            $this->appRepository->complainAndSuggestion($request,$d,$type);
            return response()->json(msg($request, success(), 'success'));
        }
        return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function aboutUs(Request $request)
    {


        $lang=$request->header('lang');
        $about = $this->appRepository->aboutUs()->select('body_'.$lang.' as body')->first();
        return response()->json(msgdata($request, success(), 'success',$about));
    }

    public function termCondition(Request $request)
    {
        $lang=$request->header('lang');

        $term = $this->appRepository->termCondition()->select('term_'.$lang.' as term')->first();
        return response()->json(msgdata($request, success(), 'success',$term));
    }

    public function issues(Request $request)
    {
        $lang=$request->header('lang');

        $issues = $this->appRepository->issues($request,$lang);
        return response()->json(msgdata($request, success(), 'success',$issues));
    }

    public function losts(Request $request)
    {
        $lang=$request->header('lang');

        $losts = $this->appRepository->losts($request,$lang);
        return response()->json(msgdata($request, success(), 'success',$losts));
    }

    public function getCrieditCards(Request $request)
    {
        $lang=$request->header('lang');

        if($user=checkJWT($request->header('jwt')))
        {
            $criedit_cards = $this->appRepository->getCrieditCards($user->id);
            return response()->json(msgdata($request, success(), 'success',$criedit_cards));
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function addCrieditCard(Request $request)
    {
        $validator  = Validator::make($request->all(),[
            'card_num' => 'required|unique:criedt_cards,card_num',
            'expire_date' => 'required',
            'cvv' => 'required',
            'name' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['status'=> 401, 'msg'=> $validator->messages()->first()]);
        }
        $lang=$request->header('lang');

        if($user=checkJWT($request->header('jwt')))
        {
            $criedit_cards = $this->appRepository->addCrieditCard($request,$user->id);
            return response()->json(msgdata($request, success(), 'success',$criedit_cards));
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function activateCrieditCard(Request $request)
    {
        $validator  = Validator::make($request->all(),[
            'criedit_card_id' => 'required|exists:criedt_cards,id',
        ]);

        if($validator->fails()){
            return response()->json(['status'=> 401, 'msg'=> $validator->messages()->first()]);
        }
        $lang=$request->header('lang');

        if($user=checkJWT($request->header('jwt')))
        {
            $this->appRepository->activateCrieditCard($request,$user->id);
            return response()->json(msg($request, success(), 'success'));
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function walletchangeStatus(Request $request)
    {
        $lang=$request->header('lang');

        if($user=checkJWT($request->header('jwt')))
        {
            $this->appRepository->walletchangeStatus($user->id);
            return response()->json(msg($request, success(), 'success'));
        }
        else return response()->json(msg($request,not_authoize(),'invalid_data'));
    }

    public function notifications(Request $request){


        $lang=$request->header('lang');
        if($user=checkJWT($request->header('jwt')))
        {
            $term = $this->appRepository->notifications($user->id,$lang);
            return response()->json(msgdata($request, success(), 'success',$term));
        }
        return response()->json(msg($request, failed(), 'invalid_data'));

    }
}
