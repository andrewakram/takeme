<?php

namespace App\Http\Controllers\Interfaces\UserUber;

interface AuthRepositoryInterface{

    public function create($attributes);
    //captin
    public function captinCompleteRegister($request,$jwt,$lang);
    public function updateLocation($request,$driver_id);
    //
    public function sendSMS($type,$phone);
    public function codeCheck($code);
    public function activeUser($phone);
    public function checkIfEmailExist($email);
    public function checkIfEmailExist2($email,$id);
    public function checkIfPhoneExist($phone);
    public function checkIfPhoneExist2($phone,$id);
    public function checkIfUserExist($id);
    public function updateEmail($id,$is_shop,$request,$lang);
    public function updatePhone($id,$is_shop,$request,$lang);
    public function editeProfile($id,$request,$lang);
    //public function checkJWT($jwt);
    public function checkId($id);
    public function userData($jwt,$is_captin,$lang);
    public function getPointOffers($country_id,$lang);
    public function convertPoints($input,$user_id,$lang);
}
