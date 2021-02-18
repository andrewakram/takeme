<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 22/05/2019
 * Time: 01:52 م
 */

namespace App\Http\Controllers\Interfaces\UserUber;


interface HomeRepositoryInterface
{
    public function home($request,$country_id,$lang);
    public function savedLocations($request,$country_id,$lang);
    public function bankAccounts($request,$lang);
    public function checkPromoCode($input,$country_id,$lang);

    //
    public function countries($lang);


}
