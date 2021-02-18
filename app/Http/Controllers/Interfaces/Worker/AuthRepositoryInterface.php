<?php

namespace App\Http\Controllers\Interfaces\Worker;

interface AuthRepositoryInterface{
    public function create($attributes);
    public function sendSMS($role,$type,$phone);
    public function checkIfEmailExists($email);
    public function checkIfPhoneExists($phone);
    public function codeCheck($code);
    public function checkJWT($jwt);
    public function checkId($id);
    public function activeWorker($phone);
    public function workerData($id,$lang);
    public function cities($lang);
}
