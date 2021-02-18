<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 22/05/2019
 * Time: 01:53 م
 */

namespace App\Http\Controllers\Eloquent\User;


use App\Http\Controllers\Interfaces\User\HomeRepositoryInterface;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Delegate;
use App\Models\Department;
use App\Models\Message;
use App\Models\MessageImage;
use App\Models\Notification;
use App\Models\OfferPoint;
use App\Models\Option;
use App\Models\Order;
use App\Models\OrderImage;
use App\Models\OrderOffer;
use App\Models\OrderProduct;
use App\Models\OrderProductVariation;
use App\Models\OrderProductVariationOption;
use App\Models\OrderRequest;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\PromoCode;
use App\Models\Shop;
use App\Models\ShopRate;
use App\Models\Slider;
use App\Models\Trip;
use App\Models\User;
use App\Models\UserAdminMessage;
use App\Models\UserAdminMessageImage;
use App\Models\UserReplacedPoint;
use App\Models\WalletRecharge;
use Carbon\Carbon;
use DB;

class HomeRepository implements HomeRepositoryInterface
{
    public $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function category($lang)
    {
        return Category::select('id', 'name', 'image')
            ->get();
    }

    public function shops($request, $lang, $user_country_id)
    {
        $shop = new Shop();
        return $shop->filterbylatlng($request->lat, $request->lng, 1000, "shops", $user_country_id);
    }

    public function shopsByCategory($request, $lang, $user_country_id)
    {
        $shop = new Shop();
        if ($request->category_id == 0)
            return $shop->filterbylatlng($request->lat, $request->lng, 500, "shops", $user_country_id);
        return $shop->filterbylatlngByCatId($request->lat, $request->lng,
            500, "shops", $request->category_id, $user_country_id);
    }

    public function shopDetails($request, $lang)
    {
        $shop = Shop::whereId($request->shop_id)
            ->orWhere('parent_id', $request->shop_id)
            ->select('id', 'name', 'rate')
            ->with(["menus" => function ($query) use ($lang) {
                $query->with('products');
            }])
            ->with('days')
            ->first();
        $currentDay = $this->getCurrentDay($shop->days);
        $shop->today_from = $currentDay['from'];
        $shop->today_to = $currentDay['to'];
        $shop->rates_count = ShopRate::where("shop_id", $request->shop_id)->count();
        //$shop->rates_count = "12300";
        return $shop;
    }

    public function shopRates($request, $lang)
    {
        if (isset($request->department_id) && $request->department_id == 3) {
//            $rates = Order::where("department_id", $request->department_id)
//                ->select('user_rate as rate', 'user_comment as comment', 'user_id', 'created_at')
//                ->where('user_rate','!=',null)
//                ->where('user_comment','!=',null)
//                ->with('user')
//                ->get();
            $rates = ShopRate::orderBy('rate', 'desc')
                ->where("department_id", $request->department_id)
                ->select('rate', 'comment', 'user_id', 'created_at')
                ->with('user')
                ->get();
            return (object)[
                'id' => 0,
                'name' => '',
                'rates' => $rates,
            ];
        }
        $rates = Shop::whereId($request->shop_id)
            ->orWhere('parent_id', $request->shop_id)
            ->select('id', 'name')
            ->with(["rates" => function ($query) use ($lang) {
                $query->orderBy('rate', 'desc')->with('user');
            }])->first();
        return $rates;
//        $rates = Shop::whereId($request->shop_id)
//            ->orWhere('parent_id', $request->shop_id)
//            ->select('id', 'name')
//            ->with(["rates" => function ($query) use ($lang) {
//                $query->with('user');
//            }])->first();
//        return $rates;
    }

    public function productDetails($request, $lang)
    {
        $product = Product::whereId($request->product_id)
            ->with(["variations" => function ($query) use ($lang) {
                $query->with('options');
            }])
            ->first();
        return $product;
    }

    public function rateShop($request, $user_id, $lang)
    {
        $rate = ShopRate::create([
            'user_id' => $user_id,
            'shop_id' => isset($request->shop_id) ? $request->shop_id : 0,
            'department_id' => isset($request->department_id) ? $request->department_id : 3,
            'rate' => $request->rate,
            'comment' => $request->comment,
        ]);

        return ShopRate::whereId($rate->id)->with('user')->first();
    }

//    public function getRates($request,$lang){
//        if(isset($request->department_id) && $request->department_id == 3)
//            return Order::where("department_id",$request->department_id)
//                ->select('user_rate as rate','user_comment as comment','user_id')
//                ->with('user')
//                ->get();
//        return ShopRate::orderBy('id','desc')
//            ->where("shop_id",$request->shop_id)
//            ->with('user')
//            ->get();
//    }

    public function homeThirdDepartment($request, $lang)
    {
//        $avg_rates = Order::where("department_id",$request->department_id)
//            ->where('user_rate','!=',null)
//            ->where('user_comment','!=',null)
//            ->avg('user_rate');
//        $rates_count =Order::where("department_id",$request->department_id)
//            ->where('user_rate','!=',null)
//            ->where('user_comment','!=',null)
//            ->count();
        $avg_rates = ShopRate::where("department_id", $request->department_id)
            ->avg('rate');
        $rates_count = ShopRate::where("department_id", $request->department_id)
            ->count();
        return (object)[
            'avg_rates' => number_format((float)($avg_rates), 1, '.', ''),
            'rates_count' => $rates_count
        ];
    }

    //
    public function getCurrentDay($shop_days)
    {
        $data['from'] = "";
        $data['to'] = "";
        foreach ($shop_days as $shop_day) {
            if ($shop_day->name_en == Carbon::now()->format('l')) {
                $data['from'] = $shop_day->from;
                $data['to'] = $shop_day->to;
                break;
            }
        }
        return $data;
//        $subset = $shop_days->map(function($day){
//            if ($day->name_en == Carbon::now()->format('l')){
//                return $day;
//            }
//        });
//        $subset = $subset->filter(function ($value){
//            return !is_null($value);
//        })->values();
//        $data['from'] = "";
//        $data['to'] = "";
//        if($subset){
//            $data['from'] = $subset->from;
//            $data['to'] = $subset->to;
//        }
    }

//    public function addToCart($request){
//
//    }

    public function makeOrder($request, $user_id, $lang = "ar", $user_country_id)
    {
        //dd($request->all());
        $delegates = Order::filterDelegates($request->in_lat, $request->in_lng, 500000, 'delegates', null, null, null, $user_country_id);
        //dd($delegates);
        if ($delegates) {
            if (isset($request->shop_id))
                $shop = Shop::whereId($request->shop_id)->first();
            $order = Order::create([
                'order_number' => $user_id . rand(100000, 999999),
                'department_id' => $request->department_id,
                'user_id' => $user_id,
                'country_id' => $user_country_id,
                'shop_id' => isset($request->shop_id) ? $request->shop_id : NULL,
                'confirm_code' => rand(100000, 999999) . $user_id,
                'title' => isset($request->title) ? $request->title : '',
                'notes' => isset($request->notes) ? $request->notes : '',
                'in_lat' => isset($request->in_lat) ? $request->in_lat : (isset($shop) ? $shop->lat : NULL),
                'in_lng' => isset($request->in_lng) ? $request->in_lng : (isset($shop) ? $shop->lng : NULL),
                'in_address' => isset($request->in_address) ? $request->in_address : (isset($shop) ? $shop->address : NULL),
                'in_city_name' => isset($request->in_city_name) ? $request->in_city_name : (isset($shop) ? $shop->city_name : NULL),
                'out_lat' => isset($request->out_lat) ? $request->out_lat : NULL,
                'out_lng' => isset($request->out_lng) ? $request->out_lng : NULL,
                'out_address' => isset($request->out_address) ? $request->out_address : NULL,
                'out_city_name' => isset($request->out_city_name) ? $request->out_city_name : NULL,
                'promo_id' => isset($request->promo_id) && $request->promo_id > 0 ? $request->promo_id : null,
                'delivery_time' => isset($request->delivery_time) ? $request->delivery_time : null,
                'total_cost' => $request->department_id == 2 ? (isset($request->total_cost) ? $request->total_cost : -1) : null,
                //'distance' => number_format($delegates->distance, 1, '.', ''),
            ]);
            if (isset($request->image)) {
                foreach ($request->image as $image)
                    OrderImage::create([
                        'order_id' => $order->id,
                        'image' => $image,
                    ]);
            }
            OrderStatus::create(['order_id' => $order->id, 'user_id' => $user_id]);
            global $total_cost;
            $total_cost = 0;
            if (isset($request->products)) {
                foreach ($request->products as $product) {
                    //
                    $product_data = Product::whereId($product['id'])->first();
                    $total_cost = $product_data->price_after * ((isset($product['quantity']) ? $product['quantity'] : 1));
                    //
                    $OrderProduct = OrderProduct::create([
                        'order_id' => $order->id,
                        'product_id' => $product['id'],
                        'quantity' => isset($product['quantity']) ? $product['quantity'] : 1,
                        'description' => $product['description'],
                    ]);
                    if (isset($product['variations'])) {
                        foreach ($product['variations'] as $variation) {
                            $OrderProductVariation = OrderProductVariation::create([
                                'order_product_id' => $OrderProduct->id,
                                'variation_id' => $variation['id'],
                            ]);
                            if (isset($variation['options'])) {
                                foreach ($variation['options'] as $option) {
                                    //
                                    $option_data = Option::whereId($option['id'])->first();
                                    $total_cost = $total_cost + ($option_data->price * ((isset($product['quantity']) ? $product['quantity'] : 1)));
                                    //
                                    OrderProductVariationOption::create([
                                        'order_product_var_id' => $OrderProductVariation->id,
                                        'option_id' => $option['id'],
                                    ]);
                                }
                            }
                        }
                    }
                }
//                $fee = (Admin::where('email', "admin@admin.com")->select('fee_percent')->first()->fee_percent) * $total_cost;
//                $total_cost = $total_cost + $fee;
                //Order::whereId($order->id)->update(['total_cost' => $total_cost]);
            }

            $title = ' طلب جديد ';
            $message = 'لديك طلب خدمة جديد,الرجاء الإستجابة';
            foreach ($delegates as $delegate) {
                OrderRequest::create([
                    'order_id' => $order->id,
                    'delegate_id' => $delegate->id,
                    'distance' => number_format($delegate->distance, 1, '.', ''),
                    'lat' => $delegate->lat,
                    'lng' => $delegate->lng,
                ]);

                Notification::send($delegate->token, $title,
                    $message, 0, 1,
                    "$order", NULL, NULL, $order->id);
            }
            return $order;
        }
        return "there is no available delegates";


    }

    public function reOrder($request, $user_id, $lang = "ar", $user_country_id)
    {
        $old_order = Order::whereId($request->order_id)
            ->with(["order_products" => function ($query) {
                $query->with(["order_product_variations" => function ($query) {
                    $query->with('order_product_variation_options');
                }]);
            }])
            ->first();
        $in_lat = isset($request->in_lat) ? $request->in_lat : $old_order->in_lat;
        $in_lng = isset($request->in_lng) ? $request->in_lng : $old_order->in_lng;
        $in_address = isset($request->in_address) ? $request->in_address : $old_order->in_address;
        $in_city_name = isset($request->in_city_name) ? $request->in_city_name : $old_order->in_city_name;
        $out_lat = isset($request->out_lat) ? $request->out_lat : $old_order->out_lat;
        $out_lng = isset($request->out_lng) ? $request->out_lng : $old_order->out_lng;
        $out_address = isset($request->out_address) ? $request->out_address : $old_order->out_address;
        $out_city_name = isset($request->out_city_name) ? $request->out_city_name : $old_order->out_city_name;
        //dd($request->all());
        $delegates = Order::filterDelegates($in_lat, $in_lng, 1000, 'delegates');
        //dd($delegates);
        if ($delegates) {

            $order = Order::create([
                'order_number' => $user_id . rand(100000, 999999),
                'department_id' => $old_order->department_id,
                'user_id' => $user_id,
                'country_id' => $user_country_id,
                'confirm_code' => rand(100000, 999999) . $user_id,
                'title' => isset($old_order->title) ? $old_order->title : '',
                'notes' => isset($old_order->notes) ? $old_order->notes : '',
                'in_lat' => $in_lat,
                'in_lng' => $in_lng,
                'in_address' => $in_address,
                'in_city_name' => $in_city_name,
                'out_lat' => $out_lat,
                'out_lng' => $out_lng,
                'out_address' => $out_address,
                'out_city_name' => $out_city_name,
                'promo_id' => isset($old_order->promo_id) && $old_order->promo_id > 0 ? $old_order->promo_id : null,
                'delivery_time' => isset($old_order->delivery_time) ? $old_order->delivery_time : null,
                'total_cost' => $request->department_id == 2 ? (isset($request->total_cost) ? $request->total_cost : -1) : null,
                //'distance' => number_format($delegates->distance, 1, '.', ''),
            ]);

            $order_images = DB::table('order_images')->where('order_id', $request->order_id)->get();
            if (isset($order_images)) {
                foreach ($order_images as $image)
                    DB::table('order_images')->insert([
                        'order_id' => $order->id,
                        'image' => $image->image
                    ]);
            }
            OrderStatus::create(['order_id' => $order->id, 'user_id' => $user_id]);
            if (isset($old_order->order_products)) {
                foreach ($old_order->order_products as $product) {
                    //
                    $OrderProduct = OrderProduct::create([
                        'order_id' => $order->id,
                        'product_id' => $product['product_id'],
                        'quantity' => isset($product['quantity']) ? $product['quantity'] : 1,
                        'description' => $product['description'],
                    ]);
                    if (isset($product['order_product_variations'])) {
                        foreach ($product['order_product_variations'] as $variation) {
                            $OrderProductVariation = OrderProductVariation::create([
                                'order_product_id' => $OrderProduct->id,
                                'variation_id' => $variation['variation_id'],
                            ]);
                            if (isset($variation['order_product_variation_options'])) {
                                foreach ($variation['order_product_variation_options'] as $option) {
                                    OrderProductVariationOption::create([
                                        'order_product_var_id' => $OrderProductVariation->id,
                                        'option_id' => $option['option_id'],
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
            $title = ' طلب جديد ';
            $message = 'لديك طلب خدمة جديد,الرجاء الإستجابة';
            foreach ($delegates as $delegate) {
                OrderRequest::create([
                    'order_id' => $order->id,
                    'delegate_id' => $delegate->id,
                    'distance' => number_format($delegate->distance, 1, '.', ''),
                    'lat' => $delegate->lat,
                    'lng' => $delegate->lng,
                ]);

                Notification::send($delegate->token, $title,
                    $message, 0, 1,
                    $order, NULL, NULL, $order->id);
            }
            return $order;
        }
        return "there is no available delegates";


    }

    public function acceptOffer($request, $user_id, $lang = 'ar')
    {
        $order_offer = OrderOffer::whereId($request->order_offer_id)->with('delegate')->first();

        if ($order_offer) {
            $order = Order::whereId($order_offer->order_id)
                ->where('user_id', $user_id)
                ->first();
            if ($order) {
                Order::whereId($order_offer->order_id)
                    ->where('user_id', $user_id)
                    ->update([
                        'delegate_id' => $order_offer->delegate_id,
                        'offer_id' => $request->order_offer_id,
                    ]);
                OrderStatus::where('order_id', $order_offer->order_id)
                    ->where('user_id', $user_id)
                    ->update([
                        'accept' => Carbon::now(),
                    ]);
                $title = ' وافق المستخدم علي عرضك ';
                $message = 'قيمة التوصيل: ' . $order_offer->offer;
                Notification::send($order_offer->delegate->token, "$title",
                    "$message", 1, 1,
                    "$order_offer", NULL, NULL, $order_offer->order_id);
                //send notification
            }
        }

    }

    public function cancelOrder($request, $user_id, $lang = 'ar')
    {
        $order = Order::whereId($request->order_id)
            ->where('user_id', $user_id)
            ->first();
        $delegate = Delegate::whereId($order->delegate_id)->first();
        if ($order)
            OrderStatus::where('order_id', $request->order_id)
                ->where('user_id', $user_id)
                ->update([
                    'cancelled' => Carbon::now(),
                    'cancel_by' => 0,
                    'cancel_reason' => isset($request->cancel_reason) ? $request->cancel_reason : "",
                ]);
        if ($delegate) {
            $title = ' قام المستخدم بإلغاء الطلب ';
            Notification::send($delegate->token, "$title",
                "$title", 0, 1,
                "", NULL, NULL, $request->order_id);
        }


    }

    public function acceptConfirmRequest($request, $user_id, $lang = 'ar')
    {
        $order = Order::whereId($request->order_id)
            ->where('user_id', $user_id)
            ->first();
        if ($order) {
            $delegate = Delegate::whereId($order->delegate_id)->select('id', 'token')->first();

            if ($request->type == 0) { // reject
                Order::whereId($request->order_id)->update(['confirm_accept' => 0]);
                $title = 'رسالة جديدة';
                $message = 'تم الغاء تأكيد الطلب من العميل';
                Message::where('confirm_accept_order', 1)
                    ->where('sender_type', 1)
                    ->where('receiver_type', 0)
                    ->where('sender_id', $delegate->id)
                    ->where('receiver_id', $user_id)
                    ->where('order_id', $request->order_id)
                    ->update(['confirm_accept_order' => 0]);
                $message = Message::create([
                    'sender_id' => $user_id,
                    'sender_type' => 0,//0=>user , 1=>delegate
                    'receiver_id' => $delegate->id,
                    'receiver_type' => 1,//0=>user , 1=>delegate
                    'message' => $message,
                    'confirm_accept_order' => 0,
                    'type' => 0,//0=>orders , 1=>trips
                    'order_id' => $request->order_id
                ]);

                Notification::send($delegate->token, $title,
                    $title, 1, 1,
                    "", "$message", NULL, $request->order_id, 0);
                //send order again to all delegates
                $delegates = Order::filterDelegates($order->in_lat, $order->in_lng, 1000, 'delegates');
                $title = ' طلب جديد ';
                $message = 'لديك طلب خدمة جديد,الرجاء الإستجابة';
                foreach ($delegates as $delegate) {
                    Notification::send("$delegate->token", $title,
                        $message, 0, 1,
                        "$order", NULL, NULL, $order->id);
                }
                //


                return $message;
            } elseif ($request->type == 2) { // accept
                Order::whereId($request->order_id)->update(['confirm_accept' => 2]);
                $title = 'رسالة جديدة';
                $message = 'تم الموافقة علي تأكيد الطلب';
                Message::where('confirm_accept_order', 1)
                    ->where('sender_type', 1)
                    ->where('receiver_type', 0)
                    ->where('sender_id', $delegate->id)
                    ->where('receiver_id', $user_id)
                    ->where('order_id', $request->order_id)
                    ->update(['confirm_accept_order' => 2]);
                $message = Message::create([
                    'sender_id' => $user_id,
                    'sender_type' => 0,//0=>user , 1=>delegate
                    'receiver_id' => $delegate->id,
                    'receiver_type' => 1,//0=>user , 1=>delegate
                    'message' => $message,
                    'confirm_accept_order' => 2,
                    'type' => 0,//0=>orders , 1=>trips
                    'order_id' => $request->order_id
                ]);

                Notification::send($delegate->token, $title,
                    $title, 1, 1,
                    "", "$message", NULL, $request->order_id, 2);
                return $message;
            }

        }

    }

    public function getOrdersOffers($request, $user_id, $lang = 'ar')
    {
        $orders = Order::orderBy('id', 'desc')
            ->where('user_id', $user_id)
            ->where('delegate_id', null)
            ->where('offer_id', null)
            ->with('shop')
            ->with(["order_status" => function ($query) {
                $query->where('accept', null)
                    ->where('on_way', null)
                    ->where('finished', null)
                    ->where('cancelled', null);
            }])
            ->select('id', 'order_number', 'title', 'notes', 'delivery_time', 'shop_id')
            ->get();
        foreach ($orders as $order) {
            $order_offers = OrderOffer::where('order_id', $order->id)
                ->count();
            $order->has_offers = $order_offers > 0 ? 1 : 0;
        }
        return $orders;
    }

    public function getOffers($request, $user_id, $lang = 'ar')
    {

        $order_offers = OrderOffer::orderBy('offer', 'asc')
            ->where('order_id', $request->order_id)
            ->with('delegate')
            ->take(1)
            ->get();
        if (sizeof($order_offers) > 0) {
            $order = Order::whereId($request->order_id)->select('counter', 'delivery_time')->first();
            $lower_offers_counter = $order->counter;
            $delivery_time = $order->delivery_time;

            $orders_count = Order::where('delegate_id', $order_offers[0]->delegate_id)->count();
            $comments_count = Order::where('delegate_id', $order_offers[0]->delegate_id)
                ->where('delegate_rate', '!=', NULL)
                ->where('delegate_comment', '!=', NULL)
                ->count();

            $order_offers[0]->delegate->orders_count = $orders_count;
            $order_offers[0]->delegate->comments_count = $comments_count;
            $order_offers[0]->lower_offers_counter = $lower_offers_counter;
            $order_offers[0]->delivery_time = $delivery_time;
        }


//        $data=[
//            'orders_count' => $orders_count,
//            'order_offers' => $order_offers,
//        ];
        return $order_offers;
    }

    public function getDelegateOrdersRates($request, $user_id, $lang = 'ar')
    {
        $orders_count = Order::where('delegate_id', $request->delegate_id)->count();
        $orders_rate = Order::where('delegate_id', $request->delegate_id)
            ->where('delegate_rate', '!=', null)
            ->where('delegate_comment', '!=', null)
            ->select('id', 'delegate_rate as rate', 'delegate_comment as comment', 'user_id', 'created_at')
            ->with('user')
            ->get();
        $data = [
            'orders_count' => $orders_count,
            'rates_count' => sizeof($orders_rate),
            'rates' => $orders_rate,
        ];
        return $data;

    }

    public function getLowerOffer($request, $user_id, $lang = 'ar')
    {
        $order = Order::whereId($request->order_id)->where('user_id', $user_id)->first();

        if ($order) {
            if ($order->counter > 2)
                return false;
            Order::whereId($request->order_id)
                ->where('user_id', $user_id)
                ->update(['counter' => $order->counter + 1]);
            $delegates = Order::filterDelegates($order->in_lat, $order->in_lng, 1000, 'delegates', null, null, null, "$order->country_id");
//dd($delegates);
            $title = ' طلب جديد ';
            $message = 'لديك طلب خدمة جديد,الرجاء الإستجابة';
            foreach ($delegates as $delegate) {

                Notification::send("$delegate->token", $title,
                    $message, 0, 1,
                    "$order", NULL, NULL, $order->id);

            }
            return true;
        }

    }

    public function changeMyDelegate($request, $user_id, $lang = 'ar')
    {
        $order = Order::whereId($request->order_id)->with('order_status')->where('user_id', $user_id)->first();
        if ($order) {
            $order_status = OrderStatus::where('order_id', $order->id)->first();
            if ($order->counter > 2)
                return false;
            //
            if ($order_status->on_way != NULL or
                $order_status->finished != NULL or
                $order_status->cancelled != NULL)
                return "cant_change_delegate";
            //

            //==
            $old_delegate = Delegate::whereId($order->delegate_id)->first();
            $title = ' طلب تغيير المندوب ';
            $message = 'قام المستخدم بتغيير المندوب';
            Notification::send("$old_delegate->token", $title,
                $message, 0, 1,
                "", NULL, NULL, $order->id);
            //==

            Order::whereId($request->order_id)
                ->where('user_id', $user_id)
                ->update([
                    'counter' => $order->counter + 1,
                    'delegate_id' => NULL,
                ]);
            OrderStatus::where('order_id', $request->order_id)
                ->update([
                    'accept' => NULL
                ]);
            $delegates = Order::filterDelegates($order->in_lat, $order->in_lng, 1000, 'delegates', null, null, null, "$order->country_id");
            $title = ' طلب جديد ';
            $message = 'لديك طلب خدمة جديد,الرجاء الإستجابة';
            foreach ($delegates as $delegate) {

                Notification::send("$delegate->token", $title,
                    $message, 0, 1,
                    "$order", NULL, NULL, $order->id);

            }
            return "delegate_changed";
        }

    }

    public function checkPromo($request, $country_id = null, $lang = 'ar')
    {
        $codeCheck = PromoCode::where("code", $request->code)
            //->where('department_id', $request->department_id)
            ->select('id', 'department_id', 'code', 'value', 'type',
                'country_ids', 'car_level_ids', 'expire_times',
                'expire_at', $lang . '_desc as description')
            ->first();
        if ($codeCheck) {
            if ((int)strtotime($codeCheck->expire_at) < (int)strtotime(Carbon::now()->format('d F Y')))
                return "code_expired";

            if ($request->department_id == 1 || $request->department_id == 2) {
                $orders = Order::where("promo_id", $codeCheck->id)->count();
                if ($orders >= $codeCheck->expire_times)
                    return "code_expired";
            } else {
                $car_level_ids = explode(',', $codeCheck->car_level_ids);
                if (!(in_array($request->car_level_id, $car_level_ids)))
                    return "invalid_code.";
                $trips = Trip::where("promo_id", $codeCheck->id)->count();
                if ($trips >= $codeCheck->expire_times)
                    return "code_expired";
            }


            $country_ids = explode(',', $codeCheck->country_ids);
            if (!(in_array($country_id, $country_ids)))
                return "invalid_code_";

            unset($codeCheck->country_ids, $codeCheck->expire_times,
                $codeCheck->expire_at, $codeCheck->created_at, $codeCheck->updated_at);

            $codeCheck->type = (int)$codeCheck->type;
            return $codeCheck;


        }
        return "invalid_code";
    }

    public function savedLocations($request, $user_id, $lang = 'ar')
    {
        global $locations;
        $locations = new Order();
        if ($request->type == 0) { //pick-up
            return $locations->where('user_id', $user_id)
                ->where('in_lat', '!=', NULL)
                ->where('in_lng', '!=', NULL)
                ->where('in_address', '!=', NULL)
                ->where('in_city_name', '!=', NULL)
                ->select('in_lat as lat', 'in_lng as lng',
                    'in_address as address', 'in_city_name as city_name')
                ->get();
        } else { //
            return $locations->where('user_id', $user_id)
                ->where('out_lat', '!=', NULL)
                ->where('out_lng', '!=', NULL)
                ->where('out_address', '!=', NULL)
                ->where('out_city_name', '!=', NULL)
                ->select('out_lat as lat', 'out_lng as lng',
                    'out_address as address', 'out_city_name as city_name')
                ->get();
        }
    }

    public function getNotifications($request, $user_id, $lang = 'ar')
    {
        //0=>user, 1=>delegate, 2=>driver
        return Notification::where('user_id', $user_id)
//            ->select('title','body','created_at')
            ->get();
    }

    public function getHistoryOrders($request, $user_id, $lang = 'ar')
    {
        ////////////////////
        if ($request->type == 0)//current_orders
            $orders_array = OrderStatus::where('user_id', $user_id)
                ->where('cancelled', NULL)
                ->where('finished', NULL)
                ->pluck('order_id');
        if ($request->type == 1)//finished_orders
            $orders_array = OrderStatus::where('user_id', $user_id)
                ->where('cancelled', NULL)
                ->where('finished', '!=', NULL)
                ->pluck('order_id');
        ////////////////////

        //1=>uber, 2=>shops, 3=>from any to any
        if ($request->department_id == 1) {
//            return Order::where('user_id',$user_id)
//                    ->where('department_id',$request->department_id)
//                    ->get();
        } elseif ($request->department_id == 2) {
            $orders = Order::orderBy('id', 'desc')
                ->whereIn('id', $orders_array)
                ->where('department_id', $request->department_id)
                ->with('order_status')
                ->with('order_images')
                ->with('shop')
                ->with('delegate')
                ->with('offer')
                ->with(["order_products" => function ($query) use ($lang) {
                    $query->with(["order_product_variations" => function ($query) use ($lang) {
                        $query->with('order_product_variation_options');
                    }]);
                }])
                ->get();
        } elseif ($request->department_id == 3) {
            $orders = Order::orderBy('id', 'desc')
                ->whereIn('id', $orders_array)
                ->where('department_id', $request->department_id)
                ->with('order_status')
                ->with('order_images')
                ->with('shop')
                ->with('delegate')
                ->with('offer')
                ->with(["order_products" => function ($query) use ($lang) {
                    $query->with(["order_product_variations" => function ($query) use ($lang) {
                        $query->with('order_product_variation_options');
                    }]);
                }])
                ->get();
        }
        return $orders;

    }

    public function getReplacedPoints($request, $user_id, $lang)
    {
        //type =>>>> 0=>user, 1=>delegate, 2=>driver
        $data = [
            'user_points' => User::whereId($user_id)->select('points')->first()->points,
            'user_replaced_points' => UserReplacedPoint::where('user_id', $user_id)
                ->where('type', 0)->get(),
        ];
        return $data;
    }

    public function getofferPoints($request, $user_id, $lang)
    {
        $data = [
            'user_points' => User::whereId($user_id)->select('points')->first()->points,
            'user_offer_points' => OfferPoint::where('used', 0)->get(),
        ];
        return $data;
    }

    public function replacePoints($request, $user_id, $lang)
    {
        $offer = OfferPoint::whereId($request->offer_point_id)->first();
        if (isset($offer) && $offer->used == 0) {
            $user_points = User::whereId($user_id)->select('points')->first()->points;
            if ($offer->points <= $user_points) {
                UserReplacedPoint::create([
                    'user_id' => $user_id,
                    'offer_point_id' => $request->offer_point_id,
                ]);
                User::whereId($user_id)->update(['points' => $user_points - $offer->points]);
                OfferPoint::whereId($request->offer_point_id)->update(['used' => 1]);
                return true;
            }
            return false;
        }

    }

    public function getUserWalletRecharges($request, $user_id, $lang)
    {
        return User::whereId($user_id)->select('id', 'wallet')->with('user_wallet_recharges')->first();
    }

    public function raiseUserWallet($request, $user_id, $lang)
    {
        $data = WalletRecharge::create([
            'payment_id' => rand(100000, 999999),
            'user_id' => $user_id,
            'amount' => $request->amount,
        ]);
        $user = User::whereId($user_id)->first();
        $user->wallet = $user->wallet + $request->amount;
        $user->save();
        return WalletRecharge::whereId($data->id)->first();
    }

    public function getUserAdminMessages($request, $user_id, $lang)
    {
        return UserAdminMessage::where('user_id', $user_id)
            ->with('user_admin_messages_image')
            ->get();
    }

    public function chatWithAdmin($request, $user_id, $lang)
    {
        $message = UserAdminMessage::create([
            'user_id' => $user_id,
            'message' => $request->message,
            'sender_type' => 0 //0=>user , 1=>admin
        ]);
        if (isset($request->image)) {
            UserAdminMessageImage::create([
                'message_id' => $message->id,
                'image' => $request->image,
            ]);
        }

        return UserAdminMessage::whereId($message->id)
            ->with('user_admin_messages_image')
            ->first();
    }

    public function getUserMessages($request, $user_id, $user_image, $lang)
    {
        $order_id = $request->order_id;
        $order = Order::whereId($order_id)->first();
        $receiver_id = $order->delegate_id; //delegate
        $order = Order::whereId($order_id)->select('id', 'delegate_id')->first();
        if ($order) {
            $delegate_info = Delegate::whereId($order->delegate_id)->select('id', 'f_name', 'l_name', 'phone')->first();
        } else {
            $delegate_info = (object)[];
        }

        $messages = DB::select("
                SELECT id,message,order_id,created_at,
                    sender_id,sender_type,receiver_id,receiver_type,confirm_accept_order
                FROM messages
                Where
                (sender_id = $user_id and sender_type = 0 AND receiver_id = $receiver_id and receiver_type = 1)
                OR
                (sender_id = $receiver_id and sender_type = 1 AND receiver_id = $user_id and receiver_type = 0)
                AND (order_id = $order_id)
                AND (type = 0)
                Order By id asc
            ");
        if ($messages) {
            if ($messages[0]->sender_id == $user_id && $messages[0]->sender_type == 0) {
                $delegate = Delegate::whereId($messages[0]->receiver_id)->select('image')->first();
            } else {
                $delegate = Delegate::whereId($messages[0]->sender_id)->select('image')->first();
            }

            foreach ($messages as $message) {
                if (isset($message)) {
                    if ($message->message == NULL) {
                        $message->message = "";
                        $message->messages_image['image'] = MessageImage::where('message_id', $message->id)->first()->image;
                    } else {
                        $message->messages_image = null;
                    }
                    $message->created_at = Carbon::parse($message->created_at)->format('d F Y H:i A');
                    $message->user_image = $user_image;
                    $message->delegate_image = $delegate->image;
                }

            }
        }
        return [
            'confirm_accept_order' => $order->confirm_accept,
            'order_status' => OrderStatus::where('order_id', $order_id)->first(),
            'delegate_info' => $delegate_info,
            'messages' => $messages,
        ];
    }

    public function sendMessage($request, $user_id, $user_image, $lang)
    {
        $message = Message::create([
            'sender_id' => $user_id,
            'sender_type' => 0,//0=>user , 1=>delegate
            'receiver_id' => $request->receiver_id,
            'receiver_type' => 1,//0=>user , 1=>delegate
            'message' => $request->message,
            'type' => 0,//0=>orders , 1=>trips
            'order_id' => $request->order_id
        ]);
        if (isset($request->image)) {
            MessageImage::create([
                'message_id' => $message->id,
                'image' => $request->image,
            ]);
        }
        $data = Message::whereId($message->id)
            ->with('messages_image')
            ->first();
        if ($lang == 'ar') {
            $title = 'رسالة جديدة';
        } else {
            $title = 'New Message';
        }
        $delegate = Delegate::whereId($request->receiver_id)->select('token')->first();
        Notification::send($delegate->token, $title,
            $title, 1, 1,
            "", $data, NULL, $request->order_id);
        return $data;
    }

    public function shopSliders($request, $lang)
    {
        return Slider::inRandomOrder()
            ->with('shop')
            ->take(10)
            ->get();
    }

    public function departments($request, $lang)
    {
        return Department::get();
    }

    public function searchShops($request, $user_id, $lang, $user_country_id)
    {
        $shop = new Shop();
        return $shop->filterbylatlngbySearchKeyForUser($request->lat, $request->lng, "500",
            "shops", null, $request->name, $user_country_id);
//        return Shop::where('name','LIKE','%' . $request->name . '%')
//            ->orWhere('description','LIKE','%' . $request->name . '%')
//            ->where('suspend',0)
//            ->select('id','name','image','department_id','category_id','description')
//            ->get();
    }

    public function getOrderStatus($request, $lang)
    {
        $order = Order::whereId($request->order_id)
            ->with('order_status')
            ->with('delegate')
            ->select('id', 'delegate_id', 'in_lat', 'in_lng', 'in_address', 'out_lat', 'out_lng', 'out_address', 'country_id')
            ->first();
        return $order;
        return [
            'order_status' => $order->order_status,
            'delegate_info' => $order->delegate,
        ];
    }

    public function rateOrder($request, $lang, $user_type)
    {
        if ($user_type == 0) { //user
            Order::whereId($request->order_id)
                ->update([
                    "delegate_rate" => $request->rate,
                    "delegate_comment" => $request->comment
                ]);
        } elseif ($user_type == 0) { //delegate
            Order::whereId($request->order_id)
                ->update([
                    "user_rate" => $request->rate,
                    "user_comment" => $request->comment
                ]);
        }

        return true;
    }

}
