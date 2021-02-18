<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 09/07/2019
 * Time: 10:52 ص
 */

namespace App\Http\Controllers\Eloquent\Admin;


use App\Http\Controllers\Interfaces\Admin\OrderRepositoryInterface;
use App\Models\ActiveRequest;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Notify;
use App\Models\Order;
use App\Models\User;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class OrderRepository implements OrderRepositoryInterface
{
    public function index()
    {
        return Order::with(['user','worker' ,'category'])
            ->latest()->paginate(50);
    }

    public function indexCost()
    {
        return Order::with(['user','worker' ,'category'])
            ->where("order_status","finish_order")
            ->latest()->paginate(50);
    }

    public function indexUserCost()
    {
        return Order::with(['user','worker' ,'category'])
            ->latest()->paginate(50);
    }

    public function view($id)
    {
        return Order::whereId($id)->with(['user','worker','category','orderImage'])->first();
    }

    public function acceptOrder($input)
    {
        $order = Order::whereId($input->order_id)->select('id','user_id')->first();

        $active_request = ActiveRequest::where('order_id',$input->order_id)->first();

        $active_request->update([
            'sent_worker_id' => $input->worker_id
        ]);

        $ar_message = 'لديك طلب خدمة جديد,الرجاء الإستجابة';
        $en_message = 'You have a new order request,please respond';

        Notification::create([
           'user_id' => $order->user_id,
           'worker_id' => $input->worker_id,
           'order_id' => $input->order_id,
           'ar_message' => $ar_message,
           'en_message' => $en_message,
           'send_to' => 'worker'
        ]);

        $worker = Worker::where('id',$input->worker_id)->select('id','token','name')->first();

        Notify::send($worker->token,'',$ar_message,$en_message,'accept');

        $ar_text = 'تم الموافقة عل طلبك من قبل '.$worker->name;
        $en_text = 'Your order accepted by '.$worker->name;

        Notification::create([
            'user_id' => $order->user_id,
            'worker_id' => $input->worker_id,
            'order_id' => $input->order_id,
            'ar_message' => $ar_text,
            'en_message' => $en_text,
            'send_to' => 'user'
        ]);

        $token = User::where('id',$order->user_id)->pluck('token');
        Notify::send($token,'',$ar_text,$en_text,'accept');
    }

    public function rejectOrder($id)
    {
        $order = Order::whereId($id)->select('id','user_id','cat_id','lat','lng','order_choice')->first();
        $filter_order = Order::filterbylatlng2($order->lat,$order->lng,50,'workers',$order->cat_id,$order->order_choice);
        if(count($filter_order) > 0)
        {
            $ar_message = 'لديك طلب خدمة جديد,الرجاء الإستجابة';
            $en_message = 'You have a new order request,please respond';

            foreach ($filter_order as $filter)
            {
                Notification::create([
                    'user_id' => $order->user_id,
                    'worker_id' => $filter->id,
                    'order_id' => $order->id,
                    'ar_message' => $ar_message,
                    'en_message' => $en_message,
                    'send_to' => 'worker'
                ]);

                $active_request = ActiveRequest::where('order_id',$order->id)->first();


                if($active_request->sent_worker_id == null)
                {
                    $active_request->update([
                        'sent_worker_id' => $filter->id
                    ]);
                }else
                {
                    ActiveRequest::create([
                        'sent_worker_id' => $filter->id,
                        'order_id' => $order->id
                    ]);
                }

                $worker = Worker::whereId($filter->id)->pluck('token');
                Notify::send($worker,$ar_message,$en_message,'order');
            }

            return true;
        }else{
            return false;
        }
    }

    public function finishOrder($id)
    {
        $order = Order::whereId($id)->select('id','user_id','cat_id','lat','lng','date as dateOfOrder')->first();

        if(strtotime(Carbon::now()->format('d F Y')) >= strtotime($order->dateOfOrder)){
            /*Carbon::parse($this->attributes['date'])->format('d F Y')*/
            Order::whereId($id)->update([
                /*"order_action" => 1,*/
                "order_status" => "admin_finishing",
            ]);
            Worker::whereId($order->worker_id)->update([
                "busy" => 0,
            ]);
            return true;
        }else{
            return false;
        }


        /*$filter_order = Order::filterbylatlng($order->lat,$order->lng,1,'workers',$order->cat_id);
        if(count($filter_order) > 0)
        {
            $ar_message = 'لديك طلب خدمة جديد,الرجاء الإستجابة';
            $en_message = 'You have a new order request,please respond';
            foreach ($filter_order as $filter)
            {
                Notification::create([
                    'user_id' => $order->user_id,
                    'worker_id' => $filter->id,
                    'order_id' => $order->id,
                    'ar_message' => $ar_message,
                    'en_message' => $en_message,
                    'send_to' => 'worker'
                ]);
                $worker = Worker::whereId($filter->id)->pluck('token');
                Notify::send($worker,$ar_message,$en_message,'order');
                $active_request = ActiveRequest::where('order_id',$order->id)->first();
                if($active_request->sent_worker_id == null)
                {
                    $active_request->update([
                        'sent_worker_id' => $filter->id
                    ]);
                }else
                {
                    ActiveRequest::create([
                        'sent_worker_id' => $filter->id,
                        'order_id' => $order->id
                    ]);
                }
                $worker = Worker::whereId($filter->id)->pluck('token');
                Notify::send($worker,$ar_message,$en_message,'order');
            }
            return true;
        }else{
            return false;
        }*/
    }

    public function search($input)
    {
        $search = Input::get('search');
        $arr_search = [
            'search' => $search,
            'select_from' => $input->from,
            'select_to' => $input->to,
            'select_main_cats' => $input->main_cats,
            'select_sub_cats' => $input->sub_cats,
            'select_service_type' => $input->service_type,
        ];

        $get_orders = new Order;

        $orders = $get_orders->search($arr_search);

        return $orders;
    }

    public function search2($input)
    {
        $search2 = Input::get('search2');
        $arr_search2 = [
            'search2' => $search2,
            'select_from' => $input->from,
            'select_to' => $input->to,
        ];

        $get_orders = new Order;

        $orders = $get_orders->search2($arr_search2);

        return $orders;
    }

    public function search3($input)
    {
        $search3 = Input::get('search3');
        $arr_search3 = [
            'search3' => $search3,
            'select_from' => $input->from,
            'select_to' => $input->to,
        ];

        $get_orders = new Order;

        $orders = $get_orders->search3($arr_search3);

        return $orders;
    }

    public function export()
    {
        $orders = Order::all();

        foreach ($orders as $order)
        {
            if($order->order_action==0) $order_action = 'Open';
            elseif($order->order_action==1) $order_action = 'Completed';
            elseif($order->order_action==2) $order_action = 'Canceled';
            elseif($order->order_action==3) $order_action = 'Rejected';

            $order['order_action'] = $order_action;
            $order['created_date'] = $order->created_at['date'];
            $order['created_time'] = $order->created_at['time'];
            $order['category'] = Category::where('id',$order->cat_id)->select('en_name')->first()->en_name;
            $order['user_name'] = User::where('id',$order->user_id)->select('name')->first()->name;
            $order['worker_name'] = isset($order->worker_id) ?
                Worker::where('id',$order->worker_id)->select('name')->first()->name : '';

            unset($order->cat_id,$order->user_id,$order->worker_id,$order->lat,$order->lng,$order->deleted_at,$order->created_at,$order->updated_at);
        }
        if($orders->count() > 0)
        {
            $orders = $orders->toArray();

            $filename = 'jaz_orders_invoice.xls';

           /* header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Cache-Control: cache, must-revalidate");
            header("Pragma: public");
            header('application/vnd.ms-excel,  charset=UTF-8, encoding=UTF-8');*/

            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: application/vnd.ms-excel, charset=UTF-8, encoding=UTF-8");

            $heads = false;
            foreach($orders as $order)
            {
                if($heads == false)
                {
                    echo implode("\t", array_keys($order)) . "\n";
                    $heads = true;
                }
                {
                    echo implode("\t", array_values($order)) . "\n";
                }
            }

            die();
        }
        else
        {
            return redirect('/admin/orders/')->with('error', 'No Result !');
        }
    }
}
