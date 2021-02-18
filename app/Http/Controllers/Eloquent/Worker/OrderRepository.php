<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 27/06/2019
 * Time: 11:06 ص
 */

namespace App\Http\Controllers\Eloquent\Worker;


use App\Http\Controllers\Interfaces\Worker\OrderRepositoryInterface;
use App\Models\ActiveRequest;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Notify;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\ThirdCatOrder;
use App\Models\User;
use App\Models\Worker;
use App\Models\WorkerThirdCat;
use Carbon\Carbon;

class OrderRepository implements OrderRepositoryInterface
{
    public function userByOrder($order_id)
    {
        return Order::whereId($order_id)->select('id', 'user_id')->first();
    }

    public function userById($user_id)
    {
        return User::where('id', $user_id)->pluck('token');
    }

    public function homeWorker($worker_id)
    {
        //date_default_timezone_set('Asia/Riyadh');
        //date_default_timezone_set('Africa/Cairo');
//        return Order::whereWorkerId($worker_id)->whereOrderAction(0)->select('id','description', 'user_id','created_at')
//            ->with('user')->latest()->get();
        $order_id = ActiveRequest::where('sent_worker_id', $worker_id)
            ->where('user_status', "!=", 2)->pluck('order_id');


        // $orders = Order::join("active_requests","active_requests.order_id","orders.id")
        //     ->where('user_status',"!=",2)
        //     ->where('order_status',"!=","user_cancelling")
        //     ->whereIn('orders.id',$order_id)->whereOrderAction(0)
        //     ->select('active_requests.user_status','orders.id','orders.description', 'orders.user_id','orders.created_at','orders.cat_id')
        //     ->with('user')->latest()->distinct("user_status")->get();

        $worker_active_requests = ActiveRequest::orderBy("order_id", "desc")
            ->where('sent_worker_id', $worker_id)
            ->where('user_status', "!=", 2)->get();
        $orders_array = [];
        foreach ($worker_active_requests as $request) {
            $order = Order::where('id', $request->order_id)
                ->where("order_status", "")
                ->with('user')
                ->select('id', 'description', 'user_id', 'created_at', 'cat_id')
                ->first();
            if ($order) {
                $order->user_status = $request->user_status;
                array_push($orders_array, $order);
            }
        }

        /*return $orders_array;*/


        foreach ($orders_array as $order) {
            $cat = Category::where('id', $order->cat_id)->select('parent_id')->first();
            if ($cat->parent_id == 3) {
                $order['cat_id'] = 3;
            }
        }
        return $orders_array;
    }

    public function orderDetails2($order_id, $worker_id, $lang)
    {
        //date_default_timezone_set('Asia/Riyadh');
        //date_default_timezone_set('Africa/Cairo');
        $worker_active_requests = ActiveRequest::where('sent_worker_id', $worker_id)
            ->where('order_id', $order_id)
            ->get();

        $orders_array = [];
        foreach ($worker_active_requests as $request) {
            $order = Order::where('id', $request->order_id)->with('user')
                ->select('orders.id', 'description', 'user_id', 'cat_id', 'type', 'date', 'time', 'address', 'order_action', 'order_status', 'orders.lat', 'orders.lng')
                ->with('user')
                ->first();
            $order->user->lat = $order->lat;
            $order->user->lng = $order->lng;
            unset($order->lat);
            unset($order->lng);
            $order->user_status = $request->user_status;
            $order->salary = isset(OrderStatus::whereOrderId($order->id)->select('salary')->first()->salary) ?
                (OrderStatus::whereOrderId($order->id)->select('salary')->first()->salary) : 0;


            if (isset($order->orderImage)) {
                $order->order_image = $order->orderImage;
            } else {
                $order->order_image = "";
            }

            $cat = Category::where('id', $order->cat_id)
                ->select('parent_id', "image", "ar_name", "en_name")
                ->first();

            //
            $workerThirdCat = WorkerThirdCat::where("cat_id", $order->cat_id)
                ->select("image as media")
                ->first();
            if ($workerThirdCat) {
                $order->image = $workerThirdCat->media;
            } else {
                $order->image = $cat->image;
            }
            //

            if ($lang == "ar") {
                $order->category = $cat->ar_name;
            } else {
                $order->category = $cat->en_name;
            }


            if ($cat->parent_id == 3) {
                $order->cat_id = 3;
            }

            array_push($orders_array, $order);

            return $orders_array[0];

        }

        /*return $orders_array;*/


        /*$order = Order::whereId($order_id)
        ->select('id','description','user_id','cat_id','type','date','time','address','order_action','order_status')
            ->with('user')->first();*/
        /*$order = Order::join("active_requests","active_requests.order_id","orders.id")
            ->where("orders.id",$order_id)
            ->select('user_status','orders.id','description','user_id','cat_id','type','date','time','address','order_action','order_status')
            ->with('user')->distinct('user_status')->first();*/

    }

    public function showWorkerThirdCat($worker_id, $lang)
    {
        return WorkerThirdCat::where('worker_id', $worker_id)
            ->select('id', $lang . '_name as name', 'ar_name', 'en_name', 'price', 'description', 'image')
            ->where("active", 1)->get();
    }

    public function checkThirdCat($input)
    {
        $order = $this->userByOrder($input->order_id);
        $third_cat_order = ThirdCatOrder::where('order_id', $input->order_id)->select('id', 'hours')->first();
        $worker_third_cat = WorkerThirdCat::where('id', $input->worker_third_id)->first();
        $salary = $worker_third_cat->price * $third_cat_order->hours;

        OrderStatus::create([
            'order_id' => $input->order_id,
            'worker_id' => $input->worker_id,
            'worker_third_cat_id' => $input->worker_third_id,
            'salary' => $salary
        ]);

        $active_request = ActiveRequest::where('order_id', $order->id)->where('sent_worker_id', $input->worker_id)->first();
        $active_request->update([
            'worker_id' => $input->worker_id,
            'user_status' => 1
        ]);

        $ar_message = 'قام العامل بالموافقة و الرد علي طلبك';
        $en_message = 'Worker accept and responded to your request ';

        $this->notification($order->user_id, $input->worker_id, $input->order_id, $ar_message, $en_message, 'user');

        $token = $this->userById($order->user_id);
        Notify::send($token, '', $ar_message, $en_message, 'third_cat', $input->order_id,
            $input->worker_id, $input->worker_third_id);
    }

    public function acceptOrder($input)
    {
        $orderStatus = Order::where("id", $input->order_id)
            ->where("order_action", 0)
            ->where("order_status", "")
            ->first();
        if ($orderStatus) {

            $act_req = ActiveRequest::where('order_id', $input->order_id)
                ->where('sent_worker_id', $input->worker_id)
                ->where('worker_id', NULL)
                ->where('user_status', 0)
                ->first();

            if ($act_req) {
                $order = $this->userByOrder($input->order_id);

                $active_request = ActiveRequest::where('order_id', $input->order_id)
                    ->where('sent_worker_id', $input->worker_id)
                    ->first();

                if ($active_request->worker_id == null) {
                    $active_request->update([
                        'worker_id' => $input->worker_id,
                        'user_status' => 1,
                    ]);
                } else {
                    ActiveRequest::create([
                        'worker_id' => $input->worker_id,
                        'sent_worker_id' => $input->worker_id,
                        'order_id' => $input->order_id,
                        'user_status' => 1,
                    ]);

                }

                $ar_message = 'قام العامل بالموافقة علي طلبك';
                $en_message = 'Worker accept your order ';

                $this->notification($order->user_id, $input->worker_id, $input->order_id, $ar_message, $en_message, 'user');

                $token = $this->userById($order->user_id);
                Notify::send($token, '', $ar_message, $en_message, 'order', $input->order_id);
                return true;

            } else {
                return false;
            }
        }


    }

    public function sendCost($input)
    {
        $order = $this->userByOrder($input->order_id);

        OrderStatus::create([
            'order_id' => $input->order_id,
            'worker_id' => $input->worker_id,
            'salary' => $input->salary,
        ]);

        $token = $this->userById($order->user_id);
        $ar_message = 'هل تقبل هذه التكلفة ' . $input->salary;
        $en_message = 'Do you Accept this cost ' . $input->salary;
        $this->notification($order->user_id, $input->worker_id, $input->order_id, $ar_message, $en_message, 'user');

        Notify::send($token, '', $ar_message, $en_message, 'cost', $input->order_id);
    }

    public function getThirdCat($lang, $worker_id)
    {
        $worker = Worker::whereId($worker_id)->select('id', 'cat_id')->first();
        $explode = explode(',', $worker->cat_id);
        $cats = Category::whereIn('parent_id', $explode)->select('id', $lang . '_name as name', 'image', 'price')->get();
        $thirdCatarray = [];
        foreach ($cats as $c) {
            $order = WorkerThirdCat::where('cat_id', $c->id)
                ->where('worker_id', $worker_id)
                ->where("active", 1)
                ->first();
            if ($order) {
                $c["is_selected"] = True;
                array_push($thirdCatarray, $c);
            } else {
                $c["is_selected"] = False;
                array_push($thirdCatarray, $c);
            }
        }
        return $thirdCatarray;
    }

    public function chooseWorkerThirdCat($input)
    {
        $worker = Worker::whereId($input->worker_id)->select('id', 'cat_id')->first();
        $explode = explode(',', $worker->cat_id);
        $cats = Category::whereIn('parent_id', $explode)->select('id')->get();

        foreach ($cats as $c) {
            WorkerThirdCat::where('cat_id', $c->id)
                ->where('worker_id', $input->worker_id)
                ->update(["active" => 0]);
        }


        if (isset($input->cat_id)) {
            $categories = Category::whereIn('id', $input->cat_id)
                ->select("id", "ar_name", "en_name", "price", "description", "image as cat_image")
                ->get();
            foreach ($categories as $category) {
                $WorkerThirdCat = WorkerThirdCat::where('cat_id', $category->id)->where("worker_id", $input->worker_id)->first();
                /*dd($WorkerThirdCat);*/
                if ($WorkerThirdCat) {
                    WorkerThirdCat::where('cat_id', $category->id)->whereWorkerId($input->worker_id)->update(["active" => 1]);
                } else {
                    /*dd("dddddddd");*/
                    WorkerThirdCat::create([
                        'cat_id' => $category->id,
                        'worker_id' => $input->worker_id,
                        'ar_name' => $category->ar_name,
                        'en_name' => $category->en_name,
                        'price' => $category->price,
                        'description' => $category->description,
                        'image' => $category->cat_image
                    ]);
                }
            }
        }


        /*foreach($categories as $category)
        {
            WorkerThirdCat::create([
                'cat_id' => $category->id,
                'worker_id' => $input->worker_id,
                'ar_name' => $category->ar_name,
                'en_name' => $category->en_name,
                'price' => $category->price,
                'description' => $category->description,
                'image' => $category->image
            ]);
        }*/
        /*$categories = Category::whereIn('id',$input->cat_id)->get();
        $worker_third_cat = WorkerThirdCat::whereIn('cat_id',$input->cat_id)->whereWorkerId($input->worker_id)->first();

        if(!isset($worker_third_cat))
        {
            foreach($categories as $category)
            {
                WorkerThirdCat::create([
                    'cat_id' => $category->id,
                    'worker_id' => $input->worker_id,
                    'ar_name' => $category->ar_name,
                    'en_name' => $category->en_name,
                    'price' => $category->price,
                    'description' => $category->description,
                    'image' => $category->image
                ]);
            }
        }*/
    }

    /*public function
    ($input)
    {
        $categories = Category::whereIn('id',$input->cat_id)->get();

        foreach($categories as $c){
            $worker_third_cat = WorkerThirdCat::where('cat_id',$c->id)->whereWorkerId($input->worker_id)->first();
            if(empty($worker_third_cat)){
                WorkerThirdCat::create([
                    'cat_id' => $c->id,
                    'worker_id' => $input->worker_id,
                    'ar_name' => $c->ar_name,
                    'en_name' => $c->en_name,
                    'price' => $c->price,
                    'description' => $c->description,
                    'image' => $c->image
                ]);
            }
        }


    }*/

    public function notification($user_id, $worker_id, $order_id, $ar_message, $en_message, $send_to)
    {
        Notification::create
        ([
            'user_id' => $user_id,
            'worker_id' => $worker_id,
            'order_id' => $order_id,
            'ar_message' => $ar_message,
            'en_message' => $en_message,
            'send_to' => $send_to,
        ]);
    }

    public function notify($token, $text)
    {
        Notify::send($token, '', $text, 'order');
    }

    public function orderDetails($order_id)
    {
        /*$order = Order::whereId($order_id)->select('id','description','user_id','cat_id','type','date','time','address','order_action','order_status')
            ->with('user')->first();*/
        $order = Order::join("active_requests", "active_requests.order_id", "orders.id")
            ->where("orders.id", $order_id)
            ->select('user_status', 'orders.id', 'description', 'user_id', 'cat_id', 'type', 'date', 'time', 'address', 'order_action', 'order_status')
            ->with('user')->distinct('user_status')->first();
        $order['salary'] = isset(OrderStatus::whereOrderId($order->id)->select('salary')->first()->salary) ?
            OrderStatus::whereOrderId($order->id)->select('salary')->first()->salary : 0;
        $order['order_image'] = $order->orderImage;
        $cat = Category::where('id', $order->cat_id)->select('parent_id')->first();
        if ($cat->parent_id == 3) {
            $order['cat_id'] = 3;
        }

        return $order;
    }

    public function changeStatus($input)
    {
        global $order;
        global $angle2;
        $order = Order::whereId($input->order_id)
            ->select('id','order_status','worker_id','user_id','date as orderDate','time as orderTime','lat','lng','type')->first();


        if($input->order_status == 'on_way')
        {
            $order->update(['order_status'=>$input->order_status]);
            $ar_message = 'الفني في الطريق.';
            $en_message = 'Worker on way';

            $token = User::whereId($order->user_id)->pluck('token');
            $this->notification($order->user_id,$order->worker_id,$order->id,$ar_message,$en_message,'user');
            Notify::send($token,'',$ar_message,$en_message,'order',$order->id);
            return true;
        }


        $latitudeFrom=$order->lat;
        $longitudeFrom=$order->lng;
        $latitudeTo=$input->lat;
        $longitudeTo=$input->lng;
        $earthRadius=6371000;
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        $angle2= $angle * $earthRadius;
        //dd($angle2);


        if($input->order_status == 'finish_order'){
            //dd($angle2);
            //date_default_timezone_set('Asia/Riyadh');
            //date_default_timezone_set('Africa/Cairo');
            //$dateNow= date('H:i:s');
            //$timeNow= date('Y-m-d');
            //H:m a
            //if ($order->orderDate <= $dateNow && $order->orderTime < $timeNow){

            if($order->type =='urgent'){
                //
                if($angle2 < 1000){
                    if($order->orderTime == NULL){
                        $order->update([
                            'order_status'=>$input->order_status,
                            'finish_price'=>$input->finish_price,
                        ]);
                        $order->save();

                        $ar_message = 'تم انهاء الطلب.';
                        $en_message = 'Order finished';
                        Worker::whereId($order->worker_id)->update([
                            'busy' => 0,
                            'online' => 1
                        ]);

                        $token = User::whereId($order->user_id)->pluck('token');
                        $this->notification($order->user_id,$order->worker_id,$order->id,$ar_message,$en_message,'user');
                        Notify::send($token,'',$ar_message,$en_message,'order',$order->id);
                        return true;
                    }
                }
                //
            }else{

                //
                if ((int)strtotime(Carbon::now()->format('d F Y')) >= (int)strtotime($order->orderDate)
                    && strtotime(date("H:i:s")) > strtotime(date("$order->orderTime")." + 10 minute") ){

                    if($angle2 < 1000){
                        $order->update([
                            'order_status'=>$input->order_status,
                            'finish_price'=>$input->finish_price
                        ]);
                        $order->save();

                        $ar_message = 'تم انهاء الطلب.';
                        $en_message = 'Order finished';
                        Worker::whereId($order->worker_id)->update([
                            'busy' => 0,
                            'online' => 1
                        ]);

                        $token = User::whereId($order->user_id)->pluck('token');
                        $this->notification($order->user_id,$order->worker_id,$order->id,$ar_message,$en_message,'user');
                        Notify::send($token,'',$ar_message,$en_message,'order',$order->id);
                        return true;
                    }


                }else{
                    return false;
                }
                //
            }




        }

        if($input->order_status == 'worker_arrived'){
            if($angle2 < 1000){
                $order->update(['order_status'=>$input->order_status]);

                $ar_message = 'الفني متواجد حاليا في موقع الطلب.';
                $en_message = 'The worker arrived at the order location now. ';

                $token = User::whereId($order->user_id)->pluck('token');
                $this->notification($order->user_id,$order->worker_id,$order->id,$ar_message,$en_message,'user');
                Notify::send($token,'',$ar_message,$en_message,'order',$order->id);
                return true;
            }
            return "outOfArea";

        }
        /*else{
                return false;
        }*/
    }

    /*public function changeStatus($input)
    {
        $order = Order::whereId($input->order_id)->select('id','order_status','worker_id','user_id')->first();
        $order->update(['order_status'=>$input->order_status]);

        if($input->order_status == 'on_way')
        {
            $ar_message = 'الفني في الطريق.';
            $en_message = 'Worker on way';

            $token = User::whereId($order->user_id)->pluck('token');
            $this->notification($order->user_id,$order->worker_id,$order->id,$ar_message,$en_message,'user');
            Notify::send($token,'',$ar_message,$en_message,'order',$order->id);
        }

        if($input->order_status == 'finish_order')
        {
            $ar_message = 'تم انهاء الطلب.';
            $en_message = 'Order finished';
            Worker::whereId($order->worker_id)->update(['busy' => 0]);

            $token = User::whereId($order->user_id)->pluck('token');
            $this->notification($order->user_id,$order->worker_id,$order->id,$ar_message,$en_message,'user');
            Notify::send($token,'',$ar_message,$en_message,'order',$order->id);
        }
    }*/

    public function cancelOrder($input)
    {
        $order = Order::whereId($input->order_id)->whereWorkerId($input->worker_id)->select('id', 'user_id', 'worker_id', 'order_action', 'order_status')->first();

        if ($order->order_status == 'on_way' || $order->order_status == 'finish_order')
            return 'cannot_cancel';
        else {
            $order->order_action = 3;
            $order->save();

            $ar_message = 'قام الفني بالغاء الطلب.';
            $en_message = 'Worker cancel the order';
            Worker::whereId($order->worker_id)->update(['busy' => 0]);

            $token = User::whereId($order->user_id)->pluck('token');
            $this->notification($order->user_id, $order->worker_id, $order->id, $ar_message, $en_message, 'user');
            Notify::send($token, '', $ar_message, $en_message, 'order', $order->id);

            return true;
        }
    }

    public function workerCancelOrder($input)
    {
        $activeRequest = ActiveRequest::where("order_id", $input->order_id)
            ->where("sent_worker_id", $input->worker_id)
            ->select('id', 'order_id', 'worker_id', 'user_status')->first();

        if ($activeRequest) {
            if ($activeRequest->user_status == 1 /*accept*/ ||
                $activeRequest->user_status == 2 /*reject*/) {

                return false;

            } else {
                Worker::whereId($activeRequest->worker_id)->update(['busy' => 0]);

                $activeRequest->user_status = 2;
                $activeRequest->save();

                return true;
            }
        }

    }
}
