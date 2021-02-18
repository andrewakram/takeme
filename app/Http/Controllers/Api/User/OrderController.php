<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Interfaces\User\AuthRepositoryInterface;
use App\Http\Controllers\Interfaces\User\OrdersRepositoryInterface;
use App\Models\Notify;
use App\Models\Order;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use DB;

class OrderController extends Controller
{
    protected $orderRepository;
    protected $userAuthRepository;
    protected $workerAuthRepository;

    public function __construct(OrdersRepositoryInterface $ordersRepository, AuthRepositoryInterface $authRepository,\App\Http\Controllers\Interfaces\Delegate\AuthRepositoryInterface $workerAuthRepository)
    {
        $this->orderRepository = $ordersRepository;
        $this->userAuthRepository = $authRepository;
        $this->workerAuthRepository = $workerAuthRepository;
    }

    public function addToCart(Request $request)
    {
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->orderRepository->addToCart($request, $user->id);

            return response()->json(msg($request, success(), 'success'));
        }
        return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function makeOrder(Request $request)
    {
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->orderRepository->makeOrder($request, $user->id);

            return response()->json(msg($request, success(), 'success'));
        }
        return response()->json(msg($request, failed(), 'invalid_data'));
    }

}
