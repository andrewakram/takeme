<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 18/06/2019
 * Time: 03:51 م
 */

namespace App\Http\Controllers\Interfaces\AppUber;


interface AppRepositoryInterface
{
    public function complainAndSuggestion($attributes,$user_id,$type);
    public function aboutUs();
    public function termCondition();
    public function issues($request,$lang);
    public function losts($request,$lang);
    public function getCrieditCards($user_id);
    public function addCrieditCard($request,$user_id);
    public function activateCrieditCard($request,$user_id);
    public function walletchangeStatus($user_id);
    public function notifications($user_id,$lang);
}
