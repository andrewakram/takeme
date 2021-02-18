<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 04/07/2019
 * Time: 02:42 م
 */

namespace App\Http\Controllers\Interfaces\Delegate;


interface DelegateRepositoryInterface
{
    public function getShops($request,$lang,$delegate_id,$delegate_country_id);
    public function subscribeAsDelegate($request, $delegate_id, $lang);
    public function changeStatus($request,$delegate_id,$lang);
    public function sendConfirmRequest($request, $delegate_id, $lang);
    public function waitingOrders($request,$delegate_id,$lang,$delegate_country_id);
    public function allWaitingOrders($request,$delegate_id,$near_orders,$lang,$delegate_country_id);
    public function myOrders($request,$delegate_id,$lang,$delegate_country_id);
    public function subscribedShops($request,$delegate_id,$lang);
    public function ratesOfOrders($delegate_id);
    public function getOrderOffers($request,$delegate_id);
    public function addOrderOffer($request,$delegate_id,$delegate_token);

    public function getDelegateMessages($request,$user_id,$user_image, $lang);
    public function sendMessage($request,$user_id,$user_image, $lang);

    public function getReplacedPoints($request,$user_id, $lang);

}
