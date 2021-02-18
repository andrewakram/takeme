<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 04/07/2019
 * Time: 02:43 م
 */

namespace App\Http\Controllers\Eloquent\Delegate;


use App\Http\Controllers\Interfaces\Delegate\DelegateRepositoryInterface;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Delegate;
use App\Models\Message;
use App\Models\MessageImage;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderOffer;
use App\Models\OrderStatus;
use App\Models\ReplacedPoint;
use App\Models\Shop;
use App\Models\ShopDelegate;
use App\Models\User;
use App\Models\UserReplacedPoint;
use App\Models\Worker;
use App\Models\WorkerThirdCat;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use DB;

class DelegateRepository implements DelegateRepositoryInterface
{

    public function getShops($request, $lang, $delegate_id,$delegate_country_id)
    {
        $shop = new Shop();
        if (isset($request->search_key))
            return $shop->filterbylatlngbySearchKey($request->lat, $request->lng, 1000, "shops", $delegate_id, $request->search_key,$delegate_country_id);
        return $shop->filterbylatlng($request->lat, $request->lng, 1000, "shops", $delegate_id ,$delegate_country_id);
//        if($request->category_id==0)
//
//        return $shop->filterbylatlngByCatId($request->lat, $request->lng,
//            500, "shops", $request->category_id);
    }

    public function subscribeAsDelegate($request, $delegate_id, $lang)
    {
        $shopDelegateCheck = ShopDelegate::where('delegate_id', $delegate_id)
            ->where('shop_id', $request->shop_id)
            ->first();
        if ($shopDelegateCheck) {
            $shopDelegateCheck->delete();
            return false;
        } else {
            ShopDelegate::create([
                'delegate_id' => $delegate_id,
                'shop_id' => $request->shop_id,
            ]);
            return true;
        }
    }

    public function waitingOrders($request, $delegate_id, $lang,$delegate_country_id)
    {
        $shop = Shop::whereId($request->shop_id)->first();
        ///
        $latitudeFrom = $shop->lat;
        $longitudeFrom = $shop->lng;
        $latitudeTo = $request->lat;//delegate lat
        $longitudeTo = $request->lng;//delegate lng
        $earthRadius = 6371;
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        $angle2 = $angle * $earthRadius;
        ///

        $order = new Order();
        $orders = $order->filterbylatlng($shop->lat, $shop->lng, 1000, "orders", null, null, "", $delegate_country_id);

        foreach ($orders as $order) {
            $distance = (double)($order->distance) + (double)($angle2);
            $order->distance = number_format($distance, 2, '.', '') . " " . "كم";
        }
        return ($orders);
    }

    public function allWaitingOrders($request, $delegate_id, $near_orders, $lang,$delegate_country_id)
    {

        $order = new Order();
        $orders = $order->filterbylatlngWaitingOrderforDelegates($request->lat, $request->lng, 1000, "orders", $delegate_id, $near_orders, null,$delegate_country_id);
        foreach ($orders as $order) {
            //
            $latitudeFrom = $order->out_lat;
            $longitudeFrom = $order->out_lng;
            $latitudeTo = $request->lat;//delegate lat
            $longitudeTo = $request->lng;//delegate lng
            $earthRadius = 6371;
            // convert from degrees to radians
            $latFrom = deg2rad($latitudeFrom);
            $lonFrom = deg2rad($longitudeFrom);
            $latTo = deg2rad($latitudeTo);
            $lonTo = deg2rad($longitudeTo);
            $latDelta = $latTo - $latFrom;
            $lonDelta = $lonTo - $lonFrom;
            $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
            $angle2 = $angle * $earthRadius;
            ///

            $distance = (double)($order->distance) + (double)($angle2);
            $order->distance = number_format($distance, 2, '.', '') . " " . "كم";
        }
        return ($orders);
    }

    public function myOrders($request, $delegate_id, $lang,$delegate_country_id)
    {
        global $data;
        $data = [];
        $order_status = request()->status;
        if (request()->department_id == 2) {
            $order = new Order();
            $orders = $order->filterbylatlng(1.1, 1.1, 1000, "orders", $delegate_id, null, "$order_status","$delegate_country_id");
            foreach ($orders as $order) {

                $shop = Shop::whereId($order->shop_id)->first();
                $order->shop = $shop;
                $latitudeFrom = $shop->lat;
                $longitudeFrom = $shop->lng;
                $latitudeTo = $request->lat;//delegate lat
                $longitudeTo = $request->lng;//delegate lng
                $earthRadius = 6371;
                // convert from degrees to radians
                $latFrom = deg2rad($latitudeFrom);
                $lonFrom = deg2rad($longitudeFrom);
                $latTo = deg2rad($latitudeTo);
                $lonTo = deg2rad($longitudeTo);
                $latDelta = $latTo - $latFrom;
                $lonDelta = $lonTo - $lonFrom;
                $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
                $angle2 = $angle * $earthRadius;
                ///
                $distance = (double)($order->distance) + (double)($angle2);
                $order->distance = number_format($distance, 2, '.', '') . " " . "كم";
                $order_cases = OrderStatus::where('order_id', $order->id )->first();
                if($order_status == 0){ // current
                    if($order_cases->finished == "")
                        array_push($data,$order);
                }elseif($order_status == 1){ // finished
                    if($order_cases->finished != "")
                        array_push($data,$order);
                }

            }
        }
        if (request()->department_id == 3) {
            $order = new Order();
            $orders = $order->filterbylatlng(1.1, 1.1, 1000, "orders", $delegate_id, null,"","$delegate_country_id");

            foreach ($orders as $order) {
                $order->shop = null;
                $latitudeFrom = $order->in_lat;
                $longitudeFrom = $order->in_lng;
                $latitudeTo = $request->lat;//delegate lat
                $longitudeTo = $request->lng;//delegate lng
                $earthRadius = 6371;
                // convert from degrees to radians
                $latFrom = deg2rad($latitudeFrom);
                $lonFrom = deg2rad($longitudeFrom);
                $latTo = deg2rad($latitudeTo);
                $lonTo = deg2rad($longitudeTo);
                $latDelta = $latTo - $latFrom;
                $lonDelta = $lonTo - $lonFrom;
                $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
                $angle2 = $angle * $earthRadius;
                ///
                $distance = (double)($order->distance) + (double)($angle2);
                $order->distance = number_format($distance, 2, '.', '') . " " . "كم";
                $order_cases = OrderStatus::where('order_id', $order->id )->first();
                if($order_status == 0){ // current
                    if($order_cases->finished == "")
                        array_push($data,$order);
                }elseif($order_status == 1){ // finished
                    if($order_cases->finished != "")
                        array_push($data,$order);
                }
                //
                if($order->department_id == 3)
                    $order->total_cost = isset($order->offer->offer) ? $order->offer->offer : 0;
            }
        }

        return ($data);
    }

    public function orderDetails($request, $delegate_id, $lang)
    {
        $orders = Order::whereId($request->order_id)
            ->with(["order_products" => function ($query) {
                $query->with(["order_product_variations" => function ($query) {
                    $query->with('order_product_variation_options');
                }]);
            }])
            ->with("order_status")
            ->with("order_images")
            ->first();
        if($orders){
            if ($orders->order_products) {
                foreach ($orders->order_products as $product) {
                    if(isset($product->order_product_variations->order_product_variation_options)){
                        global $product_details;
                        $product_details = "";
                        foreach ($product->order_product_variations->order_product_variation_options as $option) {
                            $product_details = $product_details . $option->name . ', ';
                        }
                        $product_details = substr($product_details, 0, -2);
                        $product->product_details = $product_details;
                    }
                }
            }
        }


        return $orders;
    }

    public function subscribedShops($request, $delegate_id, $lang)
    {
        $shops_ids = ShopDelegate::where('delegate_id', $delegate_id)->pluck('shop_id');
        ///
        $shops = Shop::whereIn('id', $shops_ids)->select('id', 'name', 'image', 'description')->get();
        return ($shops);
    }

    public function changeStatus($request, $delegate_id, $lang)
    {
        //1=>accept ,4=received , 2 =>on_way , 3=>finished ,

        $order = Order::whereId($request->order_id)->first();
        $user = User::whereId($order->user_id)->first();
        if ($request->status == 1) {
            //this accept is negligtable because the accept from user
            if (empty($order->delegate_id)) {
                $order->delegate_id = $delegate_id;
                $order->save();
                OrderStatus::where('order_id', $request->order_id)
                    ->update(['accept' => Carbon::now()]);
            }
            //
        }elseif ($request->status == 4) {
            if ($order->delegate_id == $delegate_id)
                OrderStatus::where('order_id', $request->order_id)
                    ->update(['received' => Carbon::now()]);

            $title = 'المندوب استلم طلبك';
            Notification::send("$user->token", $title ,
                $title , 1 ,1,
                "","",NULL,$order->id,0);
        } elseif ($request->status == 2) {
            if ($order->delegate_id == $delegate_id)
                OrderStatus::where('order_id', $request->order_id)
                    ->update(['on_way' => Carbon::now()]);

            $title = 'المندوب في الطريق';
            Notification::send("$user->token", $title ,
                $title , 1 ,1,
                "","",NULL,$order->id,0);
        } elseif ($request->status == 3) {
            $confirm_code = $request->confirm_code;
//            dd($confirm_code,$order->confirm_code,$order->delegate_id,$delegate_id);
            if($confirm_code == $order->confirm_code AND $order->delegate_id == $delegate_id){
                OrderStatus::where('order_id', $request->order_id)
                    ->update(['finished' => Carbon::now()]);
                $title = 'تم توصيل الطلب';
                Notification::send("$user->token", $title ,
                    $title , 1 ,1,
                    "","",NULL,$order->id,0);
            }else{
                return false;
            }
        }elseif ($request->status == 5) { //delegate leave order
//            $offer = OrderOffer::orderBy('id','desc')
//                ->where('order_id',$request->order_id)
//                ->where('delegate_id',$delegate_id)
//                ->first();
//            $offer->delete();
            OrderStatus::where('order_id', $request->order_id)
                    ->update(['accept' => null]);
            Order::whereId($request->order_id)
                ->update([
                    'delegate_id' => null,
                    'offer_id' => null
                ]);
            //
            $title = 'قد انسحب المندوب من توصيل طلبك';
            Notification::send("$user->token", "$title" ,
                "$title" , 1 ,1,
                "","",NULL,$order->id,0);
            //
        }

        return OrderStatus::where('order_id', $request->order_id)->first();
    }

    public function sendConfirmRequest($request, $delegate_id, $lang){
        $order = Order::whereId($request->order_id)->first();
        Order::whereId($request->order_id)->update(['confirm_accept' => 1]);
     //delegate send accept order
        $title = "رسالة جديدة";
        $message = "تم ارسال تأكيد الطلب";
        $user = User::whereId($order->user_id)->first();
        $messageChat = Message::create([
            'sender_id' => $delegate_id,
            'sender_type' => 1,//0=>user , 1=>delegate
            'receiver_id' => $order->user_id,
            'receiver_type' => 0,//0=>user , 1=>delegate
            'message' => $message,
            'confirm_accept_order' => 1,
            'type' => 0,//0=>orders , 1=>trips
            'order_id' => $request->order_id
        ]);
        Notification::send("$user->token", $title ,
            $message , 0 ,1,
            "","$messageChat",NULL,$order->id,1);

        $data = Message::whereId($messageChat->id)
            ->with('messages_image')
            ->first();
        return $data;

    }

    public function ratesOfOrders($delegate_id)
    {
        return Order::where('delegate_id', $delegate_id)
            ->where('delegate_rate','!=',null)
            ->where('delegate_comment','!=',null)
            ->with('user')
            ->select('delegate_rate as rate', 'delegate_comment as comment', 'created_at', 'user_id','country_id')
            ->get();
    }

    public function getOrderOffers($request, $delegate_id)
    {
        $order = Order::whereId($request->order_id)->with('country')->first();
        //dd($order->country->currency);
        if($order){
            $data = [];
            $maxOffer = OrderOffer::where('order_id', $request->order_id)
                ->min('offer');
            $lastOffer = OrderOffer::where('order_id', $request->order_id)
                ->where('delegate_id', $delegate_id)->first();
            $data['maxOffer'] = empty($maxOffer) ? "" : (string)$maxOffer;
            $data['lastOffer'] = empty($lastOffer) ? "" : (string)$lastOffer->status;
            $data['currency'] = $order->country->currency;

            return $data;
        }


    }

    public function addOrderOffer($request, $delegate_id,$delegate_token)
    {
        $order = Order::whereId($request->order_id)->first();
        //getMinMaxPrice($country_id, $distance)
        $order_offer_count = OrderOffer::orderBy('id', 'desc')
            ->where('delegate_id', $delegate_id)
            ->where('order_id', $request->order_id)
            ->count();
        if ($order_offer_count > 100)//must be max as 3 //TODO
            return "max_offers_limit";

        $check_order_offer = OrderOffer::orderBy('offer', 'asc')
            ->where('delegate_id', $delegate_id)
            ->where('order_id', $request->order_id)
            ->first();
        if ($check_order_offer) {
            if ($request->offer > $check_order_offer->offer)
                return false;
        }

        $order = Order::whereId($request->order_id)->with('user')->first();
        $distance1 = calculateDistanceBetweenTwoPoints($order->in_lat, $order->in_lng, $order->out_lat, $order->out_lng);
        $distance2 = calculateDistanceBetweenTwoPoints($order->in_lat, $order->in_lng, $request->lat, $request->lng);
        $total_distance = $distance1 + $distance2;

        //==
        $offer = OrderOffer::orderBy('id','desc')
            ->where('order_id',$request->order_id)
            ->where('delegate_id',$delegate_id)
            ->first();
        if($offer){
            $offer->update(['offer' => $request->offer]);
            $add = OrderOffer::whereId($offer->id)->first();
        }else{
            $add = new OrderOffer();
            $add->delegate_id = $delegate_id;
            $add->order_id = $request->order_id;
            $add->offer = $request->offer;
            $add->distance = number_format($total_distance, 1, '.', '');
            $add->save();
        }


//////
        ////

        $lower_offers_counter = Order::whereId($request->order_id)->select('counter')->first()->counter;
        $orders_count = Order::where('delegate_id',$add->delegate_id)->count();
        $comments_count = Order::where('delegate_id',$add->delegate_id)
            ->where('delegate_rate','!=',NULL)
            ->where('delegate_comment','!=',NULL)
            ->count();

        $add->delegate->orders_count = $orders_count;
        $add->delegate->comments_count = $comments_count;
        $add->lower_offers_counter = $lower_offers_counter;
        $add->delivery_time = $order->delivery_time;
        ///

            $title = ' عرض سعر ';
            $message = 'لديك عرض سعر جديد '. $request->offer;
        Notification::send($order->user->token, $title ,
            $message , 0 ,1,
            "$add",NULL,NULL,$request->order_id);

///
        //notification to user
        return true;
    }

    public function getDelegateMessages($request, $user_id, $delegate_image, $lang)
    {
        $order_id = $request->order_id;
        $order = Order::whereId($order_id)->first();
        $receiver_id = $order->user_id ; //user
        if($order){
            $user_info = User::whereId($order->user_id)->select('id','name','name','phone')->first();
        }else{
            $user_info = (object)[];
        }
        $messages = DB::select("
                SELECT id,message,order_id,created_at,
                    sender_id,sender_type,receiver_id,receiver_type,confirm_accept_order
                FROM messages
                Where
                (sender_id = $user_id and sender_type = 1 AND receiver_id = $receiver_id and receiver_type = 0)
                OR
                (sender_id = $receiver_id and sender_type = 0 AND receiver_id = $user_id and receiver_type = 1)
                AND (order_id = $order_id)
                AND (type = 0)
                Order By id asc
            ");
        if ($messages) {
            //dd($messages[0]->sender_id);
            if ($messages[0]->sender_id == $user_id && $messages[0]->sender_type == 0) {
                $user_image = User::whereId($messages[0]->sender_id)->first()->image;
            } else {
                $user_image = User::whereId($messages[0]->receiver_id)->first()->image;
            }
            foreach ($messages as $message) {
                if ($message->message == NULL) {
                    $message->message = "";
                    $message->messages_image['image'] = MessageImage::where('message_id', $message->id)->first()->image;
                } else {
                    $message->messages_image = null;
                }
                $message->created_at = Carbon::parse($message->created_at)->format('d F Y H:i A');
                $message->user_image = $user_image;
                $message->delegate_image = $delegate_image;
            }
        }

        return [
            'confirm_accept_order' => $order->confirm_accept,
            'order_status' => OrderStatus::where('order_id',$order_id)->first() ,
            'user_info' => $user_info ,
            'messages' => $messages ,
        ];
    }

    public function sendMessage($request, $user_id, $user_image, $lang)
    {
        $message = Message::create([
            'sender_id' => $user_id,
            'sender_type' => 1,//0=>user , 1=>delegate
            'receiver_id' => $request->receiver_id,
            'receiver_type' => 0,//0=>user , 1=>delegate
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
        if($lang == 'ar'){
            $title = 'رسالة جديدة';
        }else{
            $title = 'New Message';
        }
        $user = User::whereId($request->receiver_id)->select('token')->first();
        Notification::send($user->token, $title ,
            $title , 1 ,1,
            "",$data,NULL,$request->order_id);
        return $data;
    }

    public function getReplacedPoints($request, $user_id, $lang)
    {
        //type =>>>> 0=>user, 1=>delegate, 2=>driver
        $data = [
            'user_points' => Delegate::whereId($user_id)->select('points')->first()->points,
            'user_replaced_points' => ReplacedPoint::where('user_id', $user_id)
                ->where('type', 1)->get(),
        ];
        return $data;
    }


}
