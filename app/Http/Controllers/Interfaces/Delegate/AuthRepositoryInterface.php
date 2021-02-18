<?php

namespace App\Http\Controllers\Interfaces\Delegate;

interface AuthRepositoryInterface{
    public function create($attributes);
    public function sendSMS($role,$type,$phone);
    public function checkIfEmailExists($email);
    public function checkIfPhoneExists($phone);
    public function codeCheck($role,$phone,$code);
    public function checkJWT($jwt);
    public function checkId($id);
    public function activeDelegate($phone);
    public function delegateData($delegate_id,$lang);
    public function cities($lang);
    public function editeProfile($delegate_id,$request,$lang);
    public function delegateDocuments($delegate_id);
    //
    public function cashPaid($delegate_id);
    public function ordersCount($delegate_id);
    public function ratesCount($delegate_id);
    public function calculatePoints($delegate_id);
    //
    public function replacePoints($request,$delegate_id);

}
