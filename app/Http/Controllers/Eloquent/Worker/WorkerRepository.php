<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 04/07/2019
 * Time: 02:43 م
 */

namespace App\Http\Controllers\Eloquent\Worker;


use App\Http\Controllers\Interfaces\Worker\WorkerRepositoryInterface;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Worker;
use App\Models\WorkerThirdCat;
use Illuminate\Support\Facades\Hash;

class WorkerRepository implements WorkerRepositoryInterface
{


    public function services($lang,$id)
    {
        if($id == 3){
            $services = Category::whereType(2)->where('main_cat', $id)
                ->where("active",1)
                ->select('id', $lang.'_name as name', 'image','type')->get();
            return $services;
        }
        $services = Category::whereType(4)->where('main_cat', $id)
            ->where("active",1)
            ->select('id', $lang.'_name as name', 'image','type')->get();

        return $services;
    }


    public function getNotification($worker_id,$lang)
    {
        return Notification::whereWorkerId($worker_id)->whereSendTo('worker')->
            select('user_id','worker_id','order_id',$lang.'_message as message','created_at')->get();
    }

    public function getChatList($input)
    {
        return Message::whereWorkerId($input->worker_id)->select('id','user_id','order_id')->groupBy('order_id')->with('user')->get();
    }

    public function updateWorker($input,$lang)
    {
        $email_check = Worker::where('id','!=', $input->worker_id)->where('email', $input->email)->first();
        if($email_check) return 'email_exist';

        $phone_check = Worker::where('id','!=', $input->worker_id)->where('phone', $input->phone)->first();
        if($phone_check) return 'phone_exist';

        $worker = Worker::whereId($input->worker_id)->first();

        $worker->name = $input->name;

        $worker->lat = $input->lat;
        $worker->lng = $input->lng;
        $worker->address = $input->address;

        if($input->city_id)
        {
            $worker->city_id = $input->city_id;
        }

        if($input->image)
        {
            $worker->image = $input->image;
        }

        if($input->contract)
        {
            $worker->contract = $input->contract;
        }
        $worker->save();

        $name=$lang."_name";
        $worker->city_name = $worker->city->$name;

        unset($worker->city);

        return $worker;
    }

    public function updatePassword($input)
    {
        $worker = Worker::whereId($input->worker_id)->first();
        if(Hash::check($input->old_password,$worker->password))
        {
            $worker->update(['password' => Hash::make($input->new_password)]);
            return true;
        }else{
            return false;
        }
    }

    public function getWorkerThirdCat($worker_id,$lang)
    {
        return WorkerThirdCat::whereWorkerId($worker_id)
            ->select($lang.'_name as name','ar_name','en_name','price','description','image')
            ->where("active",1)->get();
    }

    public function addWorkerThirdCat($input)
    {
        if(isset($input->image)){
            $img=$input->image;
            $img_name = time().uniqid().'.'.$img->getClientOriginalExtension();
            $img->move(public_path('/public/category/images/'),$img_name);
            /*$this->attributes['image'] = $img_name ;*/

        WorkerThirdCat::create([
            'worker_id' => $input->worker_id,
            'ar_name' => $input->ar_name,
            'en_name' => $input->en_name,
            'price' => $input->price,
            'description' => $input->description,
            'image' => $img_name
        ]);
        }
    }

    public function editWorkerThirdCat($input)
    {
        $worker_third_cat = WorkerThirdCat::whereId($input->worker_third_id)->where('worker_id',$input->worker_id)->first();

        $worker_third_cat->update([
           'ar_name' => $input->ar_name,
           'en_name' => $input->en_name,
           'price' => $input->price,
           'description' => $input->description,
        ]);

        if($input->image)
        {
            $img=$input->image;
            $img_name = time().uniqid().'.'.$img->getClientOriginalExtension();
            $img->move(public_path('/public/category/images/'),$img_name);
            $worker_third_cat->update(['image' => $img_name]);
        }
    }

    public function deleteWorkerThirdCat($input)
    {
            /*$worker_third_cat = WorkerThirdCat::where('cat_id',$c->id)->whereWorkerId($input->worker_id)->first();
            $order=DB::table("order_statuses")->where("worker_third_cat_id",$worker_third_cat->id)->first();
            if(empty($order)){
                return WorkerThirdCat::where('id',$input->worker_third_id)->delete();
            }*/
       return WorkerThirdCat::where('id',$input->worker_third_id)->update([ "active" => 0 ]);
    }

    public function orderHistory($worker_id)
    {
        /*$worker_active_requests = ActiveRequest::where('worker_id',$worker_id)
            ->where('user_status',"!=",2)->get();
        $orders_array = [];
        foreach($worker_active_requests as $request){
            $order = Order::where('worker_id',$worker_id)
            ->select('id','user_id','order_status','description','created_at')
            ->where("order_status","!=","user_cancelling")
            ->with('user')->first();
            $order->user_status = $request->user_status;
            array_push($orders_array,$order);
        }
        return $orders_array;*/


        return Order::orderBy("id","desc")
            ->where('worker_id',$worker_id)
            ->select('id','user_id','order_status','description','created_at')
            ->with('user')->get();
    }

    public function showOrdersFee($worker_id)
    {
        $orders = Order::whereWorkerId($worker_id)->select('id','user_id','description')->with('user')->get();
        $fee = Admin::whereId(1)->select('id','interest_fee')->first()->interest_fee;
        foreach($orders as $order)
        {
            $order['fee'] = ($order->order_total * $fee) / 100;
        }
        return $orders;
    }

//    public function credit($worker_id)
//    {
//        $order_total1 = Order::whereWorkerId($worker_id)
//            ->where("payment_way",1) /* paymentway: cash */
//            ->sum('order_total');
//        $order_total2 = Order::whereWorkerId($worker_id)
//            ->where("payment_way",1) /* paymentway: online */
//            ->sum('order_total');
//        $fee = Admin::whereId(1)->select('id','interest_fee')->first()->interest_fee;
//
//        $admin_credit['admin_credit'] = ($order_total1 * $fee) / 100;
//        $admin_credit['worker_credit'] = ($order_total2 * (100-$fee) ) / 100;
//
//        return $admin_credit;
//    }

    public function credit($worker_id)
    {
        /* paymentway: cash */
        /*$order_total1 = Order::whereWorkerId($worker_id)
            ->where("payment_way",1)
            ->sum('order_total');*/
        /* paymentway: online */
        /*$order_total2 = Order::whereWorkerId($worker_id)
            ->where("payment_way",2)
            ->sum('order_total');*/

        $order_total = Order::whereWorkerId($worker_id)
            ->where("order_status",'finish_order')
            ->sum('finish_price');

        $fee = Admin::whereId(1)->select('id','interest_fee')->first()->interest_fee;
        $gov_fee = Admin::whereId(1)->select('id','gov_fee')->first()->gov_fee;

        //$admin_credit['admin_credit'] = ($order_total1 * $fee) / 100;
        $admin_credit['admin_credit'] = 0;
        $admin_credit['worker_credit'] = ($order_total * (100-($fee + $gov_fee) ) ) / 100;

        return $admin_credit;
    }
}
