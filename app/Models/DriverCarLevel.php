<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class DriverCarLevel extends Model
{
    use Notifiable;


    protected $table = 'driver_car_levels';

    protected $fillable = [
        'driver_id', 'car_level_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    protected $appends = [
        'car_level_name'
    ];

    public function car_level_data(){
        return $this->belongsTo(CarLevel::class,"car_level_id");
    }

    function getCarLevelNameAttribute(){

        return $this->car_level_data()->first()->name;
    }




}
