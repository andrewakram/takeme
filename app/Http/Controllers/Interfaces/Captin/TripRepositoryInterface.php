<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 27/06/2019
 * Time: 11:06 ص
 */

namespace App\Http\Controllers\Interfaces\Captin;


interface TripRepositoryInterface
{

    public function changeStatus($request,$driver_id,$country_id,$lang);
    public function calculateTripCost($request);
    public function tripHistory($driver_id,$type,$key,$lang,$driver_country_id);
    public function rateTrip($request,$driver_id,$is_captin,$lang);
    /*public function cancelOrder($attributes);*/
    
    public function collectMoney($request,$driver_id,$lang);
    public function check_rush_time($trip,$country_id);
    public function checkPromo($promo_id,$car_level_id,$country_id,$lang);

}
