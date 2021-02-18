<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 22/05/2019
 * Time: 01:52 م
 */

namespace App\Http\Controllers\Interfaces\User;


interface HomeRepositoryInterface
{
    public function category($lang);
    public function shops($request,$lang,$user_country_id);
    public function shopsByCategory($request,$lang,$user_country_id);
    public function shopDetails($request,$lang);
    public function shopRates($request,$lang);
    public function productDetails($request,$lang);
    public function rateShop($request,$user_id,$lang);
//    public function getRates($request,$lang);
    public function homeThirdDepartment($request,$lang);
    //
    public function getCurrentDay($shop_days);

    ////////////////////////

    public function makeOrder($request,$user_id,$lang,$user_country_id);
    public function reOrder($request,$user_id,$lang,$user_country_id);

    public function acceptOffer($request,$user_id,$lang);
    public function cancelOrder($request,$user_id,$lang);
    public function acceptConfirmRequest($request,$user_id,$lang);

    public function getOrdersOffers($request,$user_id,$lang);
    public function getOffers($request,$user_id,$lang);
    public function getDelegateOrdersRates($request,$user_id,$lang);
    public function getLowerOffer($request,$user_id,$lang);
    public function changeMyDelegate($request,$user_id,$lang);
    public function checkPromo($request,$country_id, $lang);
    public function savedLocations($request,$user_id,$lang);
    public function getNotifications($request,$user_id, $lang);
    public function getHistoryOrders($request,$user_id, $lang);

    public function getReplacedPoints($request,$user_id, $lang);
    public function getofferPoints($request,$user_id, $lang);
    public function replacePoints($request,$user_id, $lang);
    public function getUserWalletRecharges($request,$user_id, $lang);
    public function raiseUserWallet($request,$user_id, $lang);

    public function getUserAdminMessages($request,$user_id, $lang);
    public function chatWithAdmin($request,$user_id, $lang);

    public function getUserMessages($request,$user_id,$user_image, $lang);
    public function sendMessage($request,$user_id,$user_image, $lang);

    public function shopSliders($request, $lang);
    public function departments($request, $lang);

    public function searchShops($request,$user_id, $lang,$user_country_id);

    public function getOrderStatus($request, $lang);


    public function rateOrder($request, $lang,$user_type);
}
