<?php

namespace App\Http\Controllers\Interfaces\User;

interface OrdersRepositoryInterface{
    public function checkPromo($promo_id,$car_level_id, $country_id, $lang);
    public function addToCart($request,$user_id);
    public function makeOrder($request,$user_id);

}
