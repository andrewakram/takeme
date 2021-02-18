<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 14/07/2019
 * Time: 11:53 ص
 */

namespace App\Http\Controllers\Interfaces\Admin;


interface WorkerRepositoryInterface
{
    public function workerApp($type);
    public function workerCompany();
    public function getSubCat($id);
    public function storeWorkerCompany($attributes);
    public function getWorkerCompany($cat_id);
    public function changStatus($worker_id);
    public function activeContract($worker_id);
    public function updateWorker($attributes);
    public function search($attributes);
    public function showAdminContractPdf();
    public function uploadAdminContractPdf($attributes);
    public function editAdminContractPdf($attributes);
}
