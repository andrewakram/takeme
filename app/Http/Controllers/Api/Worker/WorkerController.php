<?php

namespace App\Http\Controllers\Api\Worker;

use App\Http\Controllers\Interfaces\Worker\AuthRepositoryInterface;
use App\Http\Controllers\Interfaces\Worker\WorkerRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class WorkerController extends Controller
{
    protected $workerRepository;
    protected $workerAuthRepository;
    public function __construct(WorkerRepositoryInterface $workerRepository, AuthRepositoryInterface $authRepository)
    {
        $this->workerRepository = $workerRepository;
        $this->workerAuthRepository = $authRepository;
    }
    
    
    
    public function services(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:categories,id,type,1'
        ]);

        if($validator->fails()) return response()->json(['status' => 'error', 'msg' => $validator->messages()->first()]);

        $services = $this->workerRepository->services($request->header('lang'),$request->id);

        return response()->json(msgdata($request,success(),'success',$services));
    }
    
    

    public function getNotification(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'worker_id' => 'required|exists:workers,id'
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $notification = $this->workerRepository->getNotification($request->worker_id,$request->header('lang'));
            return response()->json(msgdata($request,success(),'success',$notification));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function getChatList(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'worker_id' => 'required|exists:workers,id',
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $notification = $this->workerRepository->getChatList($request);
            return response()->json(msgdata($request,success(),'success',$notification));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function updateWorker(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'worker_id' => 'required|exists:workers,id',
            'name' => '',
            'email' => '',
            'phone' => '',
            'city_id' => '',
            'lat' => '',
            'lng' => '',
            'address' => ''
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $lang=$request->header('lang');
            $worker = $this->workerRepository->updateWorker($request,$lang);
            if($worker == 'email_exist')
                return response()->json(msg($request, failed(), 'email_exist'));
            elseif($worker == 'phone_exist')
                return response()->json(msg($request, failed(), 'phone_exist'));
            else
                return response()->json(msgdata($request,success(),'success',$worker));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'worker_id' => 'required|exists:workers,id',
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6',
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $worker = $this->workerRepository->updatePassword($request);
            if($worker == false)
                return response()->json(msg($request, failed(), 'old_password'));
            else
                return response()->json(msg($request,success(),'password_changed'));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function getWorkerThirdCat(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'worker_id' => 'required|exists:workers,id'
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $worker = $this->workerRepository->getWorkerThirdCat($request->worker_id,$request->header('lang'));
            return response()->json(msgdata($request,success(),'success',$worker));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function addWorkerThirdCat(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'worker_id' => 'required|exists:workers,id',
            'ar_name' => 'required',
            'en_name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'image' => 'required',
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $this->workerRepository->addWorkerThirdCat($request);
            return response()->json(msg($request,success(),'success'));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function editWorkerThirdCat(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'worker_id' => 'required|exists:workers,id',
            'worker_third_id' => 'required|exists:worker_third_cats,id',
            'ar_name' => '',
            'en_name' => '',
            'price' => 'numeric',
            'description' => '',
            'image' => 'sometimes',
        ]);
        /*$validator = Validator::make($request->all(),[
            'worker_id' => 'required|exists:workers,id',
            'worker_third_id' => 'required|exists:worker_third_cats,id,workers,id'.$request->worker_id,
            'ar_name' => 'required',
            'en_name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'image' => 'sometimes',
        ]);*/

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $worker = $this->workerRepository->editWorkerThirdCat($request);
            return response()->json(msgdata($request,success(),'success',$worker));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function deleteWorkerThirdCat(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'worker_third_id' => 'required|exists:worker_third_cats,id',
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $this->workerRepository->deleteWorkerThirdCat($request);
            return response()->json(msg($request,success(),'success'));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function orderHistory(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'worker_id' => 'required|exists:workers,id',
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $worker = $this->workerRepository->orderHistory($request->worker_id);
            return response()->json(msgdata($request,success(),'success',$worker));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function showOrdersFee(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'worker_id' => 'required|exists:workers,id',
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $orders = $this->workerRepository->showOrdersFee($request->worker_id);

            return response()->json(msgdata($request,success(),'success',$orders));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function credit(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'worker_id' => 'required|exists:workers,id',
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()->first()]);

        if($this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $admin_credit = $this->workerRepository->credit($request->worker_id);

            return response()->json(msgdata($request,success(),'success',$admin_credit));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }
}
