<?php

namespace App\Http\Controllers\Interfaces\Captin;

interface AuthRepositoryInterface{
    public function create($attributes);
    public function sendSMS($role,$type,$phone);
    public function checkIfEmailExists($email);
    public function checkIfPhoneExists($phone);
    public function codeCheck($role,$phone,$code);
    public function checkJWT($jwt);
    public function checkId($id);
    public function activeDriver($phone);
    public function workerData($id,$lang);
    public function cities($lang);
    //
    public function addBankTransfer($request,$user_id);
    public function bankingTransfers($user_id);

    public function editeProfile($delegate_id,$request,$lang);
    public function getMyCarLevels($user_id,$user_country_id);
    public function updateMyCarLevels($user_id,$user_country_id,$request);
    public function driverDocuments($driver_id);
}
