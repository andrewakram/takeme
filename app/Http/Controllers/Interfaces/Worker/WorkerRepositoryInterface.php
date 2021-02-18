<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 04/07/2019
 * Time: 02:42 م
 */

namespace App\Http\Controllers\Interfaces\Worker;


interface WorkerRepositoryInterface
{
    public function getNotification($worker_id,$lang);
    public function getChatList($attributes);
    public function updateWorker($attributes,$lang);
    public function updatePassword($attributes);
    public function getWorkerThirdCat($worker_id,$lang);
    public function addWorkerThirdCat($input);
    public function editWorkerThirdCat($input);
    public function deleteWorkerThirdCat($input);
    public function orderHistory($worker_id);
    public function showOrdersFee($worker_id);
    public function credit($worker_id);
    
    public function services($main_cat_id,$lang);
}
