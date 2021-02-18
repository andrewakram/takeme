<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\OrderRepositoryInterface;
use App\Http\Controllers\Interfaces\Admin\WorkerRepositoryInterface;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderProductVariation;
use App\Models\OrderProductVariationOption;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\OrdersExport;
use App\Exports\CostsExport;
use App\Exports\CostsUserExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use Session;

class OrderController extends Controller
{

    public function __construct()
    {
    }

    protected function index()
    {
        $type = "كل الطلبات";
        $orders = Order::with('user')
            ->with('delegate')
            ->with(["order_products" => function ($query) {
                $query->with(["order_product_variations" => function ($query) {
                    $query->with('order_product_variation_options');
                }]);
            }])
            ->with("order_images")
            ->paginate(10);
        foreach($orders as $order){
            $order->order_status = OrderStatus::where('order_id',$order->id)->first();
//            $order->order_products = OrderProduct::where('order_id',$order->id)->get();
//            if($order->order_products){
//                foreach ($order->order_products as $order_product){
//                    $order_product->order_product_variations = OrderProductVariation::where('order_product_id',$order_product->id)->get();
//                    if($order_product->order_product_variations){
//                        foreach ($order_product->order_product_variations as $order_product_variation){
//                            $order_product_variation->order_product_variation_options = OrderProductVariationOption::where('order_product_var_id',$order_product_variation->id)->get();
//                        }
//                    }
//                }
//            }
        }
//        dd($orders);
        return view('cp.orders.index',compact('orders','type'));
    }

    protected function acceptOrders()
    {
        $type = "الطلبات المقبولة";
        $orderStatusArray = OrderStatus::where('accept','!=',null)
            ->where('on_way','=',null)
            ->where('finished','=',null)
            ->where('cancelled','=',null)
            ->pluck('order_id');
        $orders = Order::whereIn('id',$orderStatusArray)
            ->with('user')
            ->with('delegate')
            ->with(["order_products" => function ($query) {
                $query->with(["order_product_variations" => function ($query) {
                    $query->with('order_product_variation_options');
                }]);
            }])
            ->with("order_images")
            ->get();
        foreach($orders as $order){
            $order->order_status = OrderStatus::where('order_id',$order->id)->first();
        }
//        dd($orders);
        return view('cp.orders.index',compact('orders','type'));
    }

    protected function onwayOrders()
    {
        $type = "طلبات في الطريق";
        $orderStatusArray = OrderStatus::where('accept','!=',null)
            ->where('on_way','!=',null)
            ->where('finished','=',null)
            ->where('cancelled','=',null)
            ->pluck('order_id');
        $orders = Order::whereIn('id',$orderStatusArray)
            ->with('user')
            ->with('delegate')
            ->with(["order_products" => function ($query) {
                $query->with(["order_product_variations" => function ($query) {
                    $query->with('order_product_variation_options');
                }]);
            }])
            ->with("order_images")
            ->get();
        foreach($orders as $order){
            $order->order_status = OrderStatus::where('order_id',$order->id)->first();
        }
//        dd($orders);
        return view('cp.orders.index',compact('orders','type'));
    }

    protected function finishedOrders()
    {
        $type = "الطلبات المنتهية";
        $orderStatusArray = OrderStatus::where('accept','!=',null)
            ->where('on_way','!=',null)
            ->where('finished','!=',null)
            ->where('cancelled','=',null)
            ->pluck('order_id');
        $orders = Order::whereIn('id',$orderStatusArray)
            ->with('user')
            ->with('delegate')
            ->with(["order_products" => function ($query) {
                $query->with(["order_product_variations" => function ($query) {
                    $query->with('order_product_variation_options');
                }]);
            }])
            ->with("order_images")
            ->get();
        foreach($orders as $order){
            $order->order_status = OrderStatus::where('order_id',$order->id)->first();
        }
//        dd($orders);
        return view('cp.orders.index',compact('orders','type'));
    }

    protected function cancelledOrders()
    {
        $type = "الطلبات الملغية";
        $orderStatusArray = OrderStatus::where('cancelled','!=',null)
            ->pluck('order_id');
        $orders = Order::whereIn('id',$orderStatusArray)
            ->with('user')
            ->with('delegate')
            ->with(["order_products" => function ($query) {
                $query->with(["order_product_variations" => function ($query) {
                    $query->with('order_product_variation_options');
                }]);
            }])
            ->with("order_images")
            ->get();
        foreach($orders as $order){
            $order->order_status = OrderStatus::where('order_id',$order->id)->first();
        }
//        dd($orders);
        return view('cp.orders.index',compact('orders','type'));
    }

}
