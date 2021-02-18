<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 27/06/2019
 * Time: 11:06 ص
 */

namespace App\Http\Controllers\Interfaces\Worker;


interface OrderRepositoryInterface
{
    public function userByOrder($order_id);
    public function userById($user_id);
    public function homeWorker($worker_id);
    public function showWorkerThirdCat($worker_id,$lang);
    public function checkThirdCat($attributes);
    public function acceptOrder($attributes);
    public function sendCost($attributes);
    public function getThirdCat($lang,$worker_id);
    public function chooseWorkerThirdCat($attributes);
    public function notification($user_id,$worker_id,$order_id,$ar_message,$en_message,$send_to);
    public function notify($token,$text);
    public function orderDetails($order_id);
    public function orderDetails2($order_id,$worker_id,$lang);
    public function changeStatus($attributes);
    public function cancelOrder($attributes);
}
