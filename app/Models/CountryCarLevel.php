<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class CountryCarLevel extends Model
{
    use Notifiable;


    protected $table = 'country_car_levels';

    protected $fillable = [
        'car_level_id','country_id',
        'start_trip_unit', 'waiting_trip_unit', 'distance_trip_unit',
        'rush_start_trip_unit','rush_waiting_trip_unit','rush_distance_trip_unit',
        'cancellation_trip_unit'
    ];

    /*public function setImageAttribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('countries/'),$img_name);
        $this->attributes['image'] = $img_name ;
    }*/

    /*public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('/countries/'.$value);
        }else{
            return asset('/default.png');
        }
    }*/



}
