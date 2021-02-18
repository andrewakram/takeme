<?php

namespace App\Http\Controllers\Eloquent\User;

use App\Http\Controllers\Interfaces\User\OrdersRepositoryInterface;
use App\Models\ActiveRequest;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\CartProductVariation;
use App\Models\Category;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Notify;
use App\Models\Order;
use App\Models\OrderImage;
use App\Models\OrderStatus;
use App\Models\PromoCode;
use App\Models\ThirdCatOrder;
use App\Models\Trip;
use App\Models\User;
use App\Models\Worker;
use App\Models\WorkerThirdCat;
use Carbon\Carbon;
use DB;

class OrdersRepository implements OrdersRepositoryInterface
{
    //public $model;

    public function __construct()
    {
        //$this->model = $model;
    }

    public function checkPromo($promo_id, $car_level_id = null, $country_id = null, $lang = 'ar')
    {
        $codeCheck = PromoCode::where("id", $promo_id)
            ->select('id', 'department_id', 'code', 'value', 'type',
                'country_ids', 'car_level_ids', 'expire_times',
                'expire_at', $lang . '_desc as description')
            ->first();
        if ($codeCheck) {
            if ((int)strtotime($codeCheck->expire_at) < (int)strtotime(Carbon::now()->format('d F Y')))
                return "code_expired";

            $car_level_ids = explode(',', $codeCheck->car_level_ids);
            if (!(in_array($car_level_id, $car_level_ids)))
                return "invalid_code.";

            $trips = Trip::where("promo_id", $codeCheck->id)->get()->count();
            if ($trips >= $codeCheck->expire_times)
                return "code_expired";

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

    public function addToCart($request, $user_id)
    {
        //
        $cart_check = Cart::where('user_id', $user_id)->first();
        if (!$cart_check) {
            $cart = Cart::create([
                'user_id' => $user_id,
                'notes' => $request->notes,
            ]);
        } else {
            $cart = $cart_check;
        }
        //
        $cart_product_check = CartProduct::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)->first();
        if (!$cart_product_check) {
            $cart_product = CartProduct::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'description' => $request->description,
            ]);
        } else {
            $cart_product = $cart_product_check;
            $cart_product->update([
                'quantity' => $request->quantity,
                'description' => $request->description,
            ]);
        }
        //
        if ($request->vatiations_id) {
            foreach ($request->vatiations_id as $vatiation) {
                $cartProductVariation = CartProductVariation::create([
                    'cart_product_id' => $cart_product->id,
                    'variation_id' => $vatiation,
                ]);
                //
                if ($request->options_id) {
                    foreach ($request->options_id as $option) {
                        CartProductVariation::create([
                            'cart_product_variation_id' => $cartProductVariation,
                            'option_id' => $option,
                        ]);
                    }
                }
                //
            }
        }


    }

    public function makeOrder($request, $user_id,$user_country_id)
    {
        $order = Order::create([
            'order_number' => $user_id . rand(100000, 999999),
            'department_id' => $request->department_id,
            'user_id' => $user_id,
            'country_id' => $user_country_id,
            'title' => isset($request->title) ? $request->title : '',
            'notes' => isset($request->notes) ? $request->notes : '',
            'in_lat' => isset($request->in_lat) ? $request->in_lat : '',
            'in_lng' => isset($request->in_lng) ? $request->in_lng : '',
            'in_address' => isset($request->in_address) ? $request->in_address : '',
            'out_lat' => isset($request->out_lat) ? $request->out_lat : '',
            'out_lng' => isset($request->out_lng) ? $request->out_lng : '',
            'out_address' => isset($request->out_address) ? $request->out_address : '',
        ]);
        if (isset($request)) {
            foreach ($request->image as $image)
                OrderImage::create([
                    'order_id' => $order,
                    'image' => $image,
                ]);
        }
        dd('nn');
    }


}
