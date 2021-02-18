<?php

namespace App\Http\Controllers\Api\Delegate;

use App\Http\Controllers\Interfaces\Delegate\AuthRepositoryInterface;
use App\Http\Controllers\Interfaces\Delegate\DelegateRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DelegateController extends Controller
{
    protected $delegateRepository;
    public function __construct(DelegateRepositoryInterface $delegateRepository)
    {
        $this->delegateRepository = $delegateRepository;
    }

    public function getShops(Request $request)
    {
        if ($d = checkDelegateJWT($request->header('jwt'))) {
            if($d->accept == 0) return response()->json(msg($request, failed(), 'activated_waiting'));

            $data = $this->delegateRepository->getShops($request,$request->header('lang'),$d->id,$d->country_id);
            return response()
                ->json(msgdata($request, success(), 'success',['shops' => $data , 'files_completed' => $d->files_completed]));
        } else return response()->json(msg($request, not_authoize(), 'invalid_data'));

    }

    public function subscribeAsDelegate(Request $request)
    {
        if ($d = checkDelegateJWT($request->header('jwt'))) {
            if($d->accept == 0) return response()->json(msg($request, failed(), 'activated_waiting'));

            $data = $this->delegateRepository->subscribeAsDelegate($request,$d->id,$request->header('lang'));
            if($data)
                return response()->json(msg($request, success(), 'delegate_subscribed'));
            return response()->json(msg($request, failed(), 'delegate_unsubscribed'));

        } else return response()->json(msg($request, not_authoize(), 'invalid_data'));

    }

    public function waitingOrders(Request $request)
    {
        if ($d = checkDelegateJWT($request->header('jwt'))) {
            if($d->accept == 0) return response()->json(msg($request, failed(), 'activated_waiting'));

            $data = $this->delegateRepository->waitingOrders($request,$d->id,$request->header('lang'),$d->country_id);
            return response()->json(msgdata($request, success(), 'success',$data));
        } else return response()->json(msg($request, not_authoize(), 'invalid_data'));

    }

    public function allWaitingOrders(Request $request)
    {
        if ($d = checkDelegateJWT($request->header('jwt'))) {
            if($d->accept == 0) return response()->json(msg($request, failed(), 'activated_waiting'));

            $data = $this->delegateRepository
                ->allWaitingOrders($request,$d->id,$d->near_orders,$request->header('lang'),$d->country_id);
            return response()->json(msgdata($request, success(), 'success',$data));
        } else return response()->json(msg($request, not_authoize(), 'invalid_data'));

    }

    public function myOrders(Request $request)
    {
        if ($d = checkDelegateJWT($request->header('jwt'))) {
            if($d->accept == 0) return response()->json(msg($request, failed(), 'activated_waiting'));

            $data = $this->delegateRepository->myOrders($request,$d->id,$request->header('lang'),$d->country_id);
            return response()->json(msgdata($request, success(), 'success',$data));
        } else return response()->json(msg($request, not_authoize(), 'invalid_data'));

    }

    public function orderDetails(Request $request)
    {
        if ($d = checkDelegateJWT($request->header('jwt'))) {
            if($d->accept == 0) return response()->json(msg($request, failed(), 'activated_waiting'));

            $data = $this->delegateRepository->orderDetails($request,$d->id,$request->header('lang'));
            return response()->json(msgdata($request, success(), 'success',$data));
        } else return response()->json(msg($request, not_authoize(), 'invalid_data'));

    }

    public function subscribedShops(Request $request)
    {
        if ($d = checkDelegateJWT($request->header('jwt'))) {
            if($d->accept == 0) return response()->json(msg($request, failed(), 'activated_waiting'));

            $data = $this->delegateRepository->subscribedShops($request,$d->id,$request->header('lang'));
            return response()
                ->json(msgdata($request, success(), 'success',['shops' => $data , 'files_completed' => $d->files_completed]));
        } else return response()->json(msg($request, not_authoize(), 'invalid_data'));

    }

    public function changeStatus(Request $request)
    {
        if ($d = checkDelegateJWT($request->header('jwt'))) {
            if($d->accept == 0) return response()->json(msg($request, failed(), 'activated_waiting'));

            $data = $this->delegateRepository->changeStatus($request,$d->id,$request->header('lang'));
            if($data)
                return response()->json(msgdata($request, success(), 'success',$data));
            return response()->json(msg($request, failed(), 'invalid_code'));

        } else return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function sendConfirmRequest(Request $request)
    {
        if ($d = checkDelegateJWT($request->header('jwt'))) {
            if($d->accept == 0) return response()->json(msg($request, failed(), 'activated_waiting'));

            $data = $this->delegateRepository->sendConfirmRequest($request,$d->id,$request->header('lang'));
            if($data)
                return response()->json(msgdata($request, success(), 'success',$data));
            return response()->json(msg($request, failed(), 'invalid_code'));

        } else return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function ratesOfOrders(Request $request)
    {
        if ($d = checkDelegateJWT($request->header('jwt'))) {

            if ($d) {
                $rates = $this->delegateRepository->ratesOfOrders($d->id);

                return response()
                    ->json(msgdata($request, success(), 'success',$rates ));
            }
        } else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function getOrderOffers(Request $request)
    {
        if ($d = checkDelegateJWT($request->header('jwt'))) {

            if ($d) {
                $data = $this->delegateRepository->getOrderOffers($request,$d->id);

                return response()
                    ->json(msgdata($request, success(), 'success',$data ));
            }
        } else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function addOrderOffer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'offer' => 'numeric',
            'lat' => 'required',
            'lng' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 401, 'msg' => $validator->messages()->first()]);
        }
        if ($d = checkDelegateJWT($request->header('jwt'))) {


            $data = $this->delegateRepository->addOrderOffer($request,$d->id,$d->token);

            return response()
                ->json(msg($request, success(), 'success'));

        } else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function getChatMessages(Request $request)
    {
        if ($d = checkDelegateJWT($request->header('jwt'))) {

            if ($d) {
                $data = $this->delegateRepository->getChatMessages($request,$d->id);

                return response()
                    ->json(msgdata($request, success(), 'success',$data));
            }
        } else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function getDelegateMessages(Request $request)
    {
        if ($user = checkDelegateJWT($request->header('jwt'))) {
            $data = $this->delegateRepository
                ->getDelegateMessages($request,$user->id,$user->image,$request->header('lang'));

            return response()->json(msgdata($request, success(), 'success', $data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function sendMessage(Request $request)
    {
        if ($user = checkDelegateJWT($request->header('jwt'))) {
            $data = $this->delegateRepository
                ->sendMessage($request,$user->id,$user->image,$request->header('lang'));

            return response()->json(msgdata($request, success(), 'success', $data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function getReplacedPoints(Request $request)
    {
        if ($user = checkDelegateJWT($request->header('jwt'))) {
            $data = $this->delegateRepository->getReplacedPoints($request,$user->id,$request->header('lang'));

            return response()->json(msgdata($request, success(), 'success', $data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }



}
