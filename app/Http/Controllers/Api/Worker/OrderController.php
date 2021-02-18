<?php

namespace App\Http\Controllers\Api\Worker;

use App\Http\Controllers\Interfaces\User\UserRepositoryInterface;
use App\Http\Controllers\Interfaces\Worker\AuthRepositoryInterface;
use App\Http\Controllers\Interfaces\Worker\OrderRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;

class OrderController extends Controller
{
    protected $orderRepository;
    protected $workerAuthRepository;
    protected $userRepository;
    public function __construct(OrderRepositoryInterface $orderRepository, AuthRepositoryInterface $authRepository, UserRepositoryInterface $userRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->workerAuthRepository = $authRepository;
        $this->userRepository = $userRepository;
    }

    public function homeWorker(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'worker_id' => 'required|exists:workers,id'
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $workers = $this->orderRepository->homeWorker($request->worker_id);
            return response()->json(msgdata($request,success(),'success',$workers));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function showWorkerThirdCat(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'worker_id' => 'required|exists:workers,id'
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $workers = $this->orderRepository->showWorkerThirdCat($request->worker_id,$request->header('lang'));
            return response()->json(msgdata($request,success(),'success',$workers));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function checkThirdCat(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'order_id' => 'required|exists:orders,id',
            'worker_id' => 'required|exists:workers,id',
            'worker_third_id' => 'required|exists:worker_third_cats,id'
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $this->orderRepository->checkThirdCat($request);
            return response()->json(msg($request,success(),'success'));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function getThirdCat(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'worker_id' => 'required|exists:workers,id',
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $third_cat = $this->orderRepository->getThirdCat($request->header('lang'),$request->worker_id);
            return response()->json(msgdata($request,success(),'success',$third_cat));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function chooseWorkerThirdCat(Request $request)
    {
        $validator = Validator::make($request->all(),[
            /*'cat_id' => 'required|array|exists:categories,id,type,3',*/
            'cat_id' => 'exists:categories,id,type,3',
            'worker_id' => 'required|exists:workers,id'
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $this->orderRepository->chooseWorkerThirdCat($request);
            return response()->json(msg($request,success(),'success'));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function acceptOrder(Request $request)
    {
        $validator = Validator::make($request->all(),[
//            'cat_id' => 'required|array|exists:categories,id,type,3',
            'worker_id' => 'required|exists:workers,id'
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            /*$order = Order::where("id",request('order_id'))
                ->where("worker_id",request("worker_id"))
                ->where("order_status","accept_order")
                ->get();
            if($order){
                return response()->json([
                    "status" => "failed",
                    "msg" => "Order accepted before"
                ]);
            }*/
            if($this->orderRepository->acceptOrder($request)){
                return response()->json(msg($request,success(),'success'));
            }else{
                return response()->json(msg($request,success(),'order_status_changed'));
            }

        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function sendCost(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'order_id' => 'required|exists:orders,id',
            'worker_id' => 'required|exists:workers,id',
            'salary' => 'required'
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $this->orderRepository->sendCost($request);
            return response()->json(msg($request,success(),'success'));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function orderDetails(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'order_id' => 'required|exists:orders,id'
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $orders = $this->orderRepository
                ->orderDetails2($request->order_id,$request->worker_id,$request->header('lang'));
            return response()->json(msgdata($request,success(),'success',$orders));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function changeStatus(Request $request)
    {
        if($request->order_status == "finish_order"){
            $validator = Validator::make($request->all(),[
                'finish_price' => 'required|numeric'
            ]);
            if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);
        }

        $validator = Validator::make($request->all(),[
            'order_id' => 'required|exists:orders,id',
            'order_status' => 'required|in:on_way,finish_order,worker_arrived'
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $result=$this->orderRepository->changeStatus($request);
            if ($result == true){
                return response()->json(msg($request,success(),'success'));
            }elseif($result == "outOfArea"){
                return response()->json(msg($request,failed(),'outOfArea'));
            }
            else{
                return response()->json(msg($request,failed(),'order_in_10'));
            }
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function cancelOrder(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'order_id' => 'required|exists:orders,id,worker_id,'.$request->worker_id,
            'worker_id' => 'required|exists:workers,id',
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $cancel_order = $this->orderRepository->cancelOrder($request);
            if($cancel_order)
                return response()->json(msg($request,failed(),'not_cancel'));
            else
                return response()->json(msg($request,success(),'success'));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function workerCancelOrder(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'order_id' => 'required|exists:active_requests,order_id',
            'worker_id' => 'required|exists:workers,id',
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $worker_cancel_order = $this->orderRepository->workerCancelOrder($request);
            if($worker_cancel_order){
                return response()->json(msg($request,success(),'worker_cancel_order'));
            }else{
                return response()->json(msg($request,failed(),'worker_not_cancel'));
            }

        }else{
            return response()->json(msg($request, failed(),'invalid_data'));
        }
    }

}
