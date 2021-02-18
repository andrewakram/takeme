<?php

namespace App\Http\Controllers\Interfaces\UserUber;

interface TripRepositoryInterface{

    public function calculateTripPrices($request,$country_id,$lang);

    public function addLocation($request,$user_id,$lang);
    public function getLocations($user_id,$lang);

    public function cancellingReasons($is_captin,$lang);

    public function createTrip($request,$user_id,$userlat,$userlng,$lang,$user_country_id);
    public function cancelTrip($request,$lang);
    public function tripDetails($request,$user_id,$lang);
    public function tripHistory($user_id,$lang);
    public function chatHistory($request,$user_id,$lang);
    public function addMessage($request,$user_id,$lang);

    ///// ------- cron job func --------
    public function scheduledTrip();



}
