<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Interfaces\App\AppRepositoryInterface;
use App\Http\Controllers\Interfaces\User\AuthRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AppController extends Controller
{
    protected $appRepository;
    protected $userAuthRepository;

    public function __construct(AppRepositoryInterface $appRepository, AuthRepositoryInterface $userAuthRepository)
    {
        $this->appRepository = $appRepository;
        $this->userAuthRepository = $userAuthRepository;
    }

    public function getFeePercent(Request $request)
    {
        $data = $this->appRepository->getFeePercent();
        return response()->json(msgdata($request, success(), 'success', $data));
    }

    public function countries(Request $request)
    {
        $data = $this->appRepository->countries();
        return response()->json(msgdata($request, success(), 'success', $data));
    }

    public function cars_nationals(Request $request)
    {
        $carTpes = $this->appRepository->carTpes();
        $nationalTypes = $this->appRepository->nationalTypes();
        return response()->json(msgdata($request, success(), 'success', ['car_types' => $carTpes , 'national_types' => $nationalTypes]));
    }

    public function countriesCodes(Request $request)
    {
        $data = $this->appRepository->countriesCodes();
        return response()->json(msgdata($request, success(), 'success', $data));
    }

    public function cities(Request $request)
    {
        $data = $this->appRepository->cities($request);
        return response()->json(msgdata($request, success(), 'success', $data));
    }

    public function complainSuggest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:complain,suggest',
            'user_id' => 'required|exists:users,id',
            'title' => 'required|max:190',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()]);
        }

        if ($this->userAuthRepository->checkJWT($request->header('jwt'))) {
            $this->appRepository->complainAndSuggestion($request);
            return response()->json(msg($request, success(), 'success'));
        }
        return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function aboutUs(Request $request)
    {
        if ($request->header('lang') == 'ar')
            $about = $this->appRepository->aboutUs()
                ->where('type',$request->type)
                ->select('body_ar as body')->first();
        else
            $about = $this->appRepository->aboutUs()
                ->where('type',$request->type)
                ->select('body_en as body')->first();
        return response()->json(msgdata($request, success(), 'success', $about));
    }

    public function termCondition(Request $request)
    {
        if ($request->header('lang') == 'ar')
            $term = $this->appRepository->termCondition()
                ->where('type',$request->type)
                ->select('term_ar as name')
                ->first();
        else
            $term = $this->appRepository->termCondition()
                ->where('type',$request->type)
                ->select('term_en as name')
                ->first();
        return response()->json(msgdata($request, success(), 'success', $term));
    }

    public function contactUs(Request $request)
    {
        $term = $this->appRepository->contactUs($request);
        return response()->json(msg($request, success(), 'success'));
    }

    public function appExplanation(Request $request, $type = 0)
    {
        if ($request->header('lang') == 'ar')
            $data = $this->appRepository->appExplanation()
                ->where('type', $type)
                ->select('ar_title as title', 'ar_body as body', 'image')->get();
        else
            $data = $this->appRepository->appExplanation()
                ->where('type', $type)
                ->select('en_title as title', 'en_body as body', 'image')->get();
        return response()->json(msgdata($request, success(), 'success', $data));
    }

    public function getNotifications(Request $request)
    {
        //type  >>> 0=>user, 1=>delegate, 2=>driver
        if($request->type == 0){
            if ($user = checkJWT($request->header('jwt'))) {
                $data = $this->appRepository->getNotifications($request,$user->id,$request->header('lang'));

                return response()->json(msgdata($request, success(), 'success', $data));
            }
        }elseif($request->type == 1){
            if ($user = checkDelegateJWT($request->header('jwt'))) {
                $data = $this->appRepository->getNotifications($request,$user->id,$request->header('lang'));

                return response()->json(msgdata($request, success(), 'success', $data));
            }
        }
        elseif($request->type == 2){
            if ($user = checkDriverJWT($request->header('jwt'))) {
                $data = $this->appRepository->getNotifications($request,$user->id,$request->header('lang'));

                return response()->json(msgdata($request, success(), 'success', $data));
            }
        }

        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }
}
