<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class TripPath extends Model
{
    protected $softDelete = true;
    /* status: 0=pickup , 1=firstLocation , 2=secondLocation */
    protected $fillable = [
        'trip_id','status','address','lat','lng',
    ];

    protected $hidden = [
        'trip_id'
    ];

    /*public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }*/

    public static function filterbylatlng($mylat,$mylng,$radius,$model)
    {
        $haversine = "(6371 * acos(cos(radians($mylat))
                           * cos(radians($model.lat))
                           * cos(radians($model.lng)
                           - radians($mylng))
                           + sin(radians($mylat))
                           * sin(radians($model.lat))))";
        $datainradiusrange = User::orderBy('users.id','desc')
            ->join("captin_infos","captin_infos.user_id","users.id")
            ->select('users.id','users.name','users.phone','users.lat','users.lng',
                'users.image','car_color','car_num','car_model','car_level')
            ->selectRaw("{$haversine} AS distance")
            ->whereRaw("{$haversine} < ?", [$radius])
            ->get();

        return $datainradiusrange;
    }

//    function getStatusAttribute($value)
//    {
//        /*if($value == 0){
//
//        }*/
//    }

    function getCreatedAtAttribute()
    {
        return  Carbon::parse($this->attributes['created_at'])
            ->format('Y/m/d , H:i a');
    }

    function getStatusAttribute($val){
        //
        if($val == null){
            return  0;
        }else{
            return $val;
        }
    }
}
