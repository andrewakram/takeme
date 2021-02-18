<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;


class Trip extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    /*status: 1=waiting_captin , 2=trip_started , 3=trip_finished , 4=trip_cancelled*/
    protected $fillable = [
        'status','cancel_id','canceled_by','cancel_reason','user_id','driver_id','','cancel_reason',
        'start_address','satart_lat','start_lng',
        'end_address','endt_lat','end_lng',
        'description','country_id','pay_status'
    ];

    protected $appends = ['currency','created_at_time'];
    function getCurrencyAttribute()
    {
        if(isset($this->attributes['country_id'])){
            return (string)Country::whereId($this->attributes['country_id'])->first()->currency;
        }
        //$country_id = $this->attributes['country_id'];
        if(!isset($this->attributes['country_id'])){
            return "درهم اماراتي";
        }
//            $country_id=2;
//

    }

    public function trip_paths()
    {
        $data = $this->hasMany(TripPath::class, 'trip_id');
        if($data)
            return $data;
        return (object)[];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')
            ->select('id','name','phone','lat','lng','image','rate','country_id','promo_code','no_of_trips');
    }

    public function driver()
    {
        $data = $this->belongsTo(Driver::class, 'driver_id');
//           ->select('id','f_name','l_name','phone','lat','lng','image','rate','country_id','promo_code');
        return $data;
    }

    public static function filterbylatlng($mylat,$mylng,$radius,$model,$country_id=188)
    {
        $haversine = "(6371 * acos(cos(radians($mylat))
                           * cos(radians($model.lat))
                           * cos(radians($model.lng)
                           - radians($mylng))
                           + sin(radians($mylat))
                           * sin(radians($model.lat))))";
        $datainradiusrange = Driver::orderBy('id','desc')
            ->select('id','f_name','l_name','lat','lng','image','car_color','car_num','car_text')
            ->selectRaw("{$haversine} AS distance")
            ->whereRaw("{$haversine} < ?", [$radius])
            ->where('files_completed', 1)
            ->where('active', 1)
            ->where('suspend', 0)
            ->where('accept', 1)//captin_infos
            ->where('busy', 0)//captin_infos
            ->where('online', 1)//captin_infos
//            ->where('country_id', $country_id)
            ->get();
//dd($datainradiusrange);
        return $datainradiusrange;
    }

    public static function calc_distance($lat_1,$lng_1,$lat_2,$lng_2)
    {

        $latitudeFrom=$lat_1;
        $longitudeFrom=$lng_1;
        $latitudeTo=$lat_2;
        $longitudeTo=$lng_2;
        $earthRadius=6371;
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle2= $angle * $earthRadius;

    }

    function getTripDistanceAttribute($value)
    {
        if($value)
            return  "$value";
        return  "";
    }

    function getCreatedAtAttribute()
    {
        return  Carbon::parse($this->attributes['created_at'])
            ->format('Y/m/d , H:i a');
    }

    function getCreatedAtTimeAttribute()
    {
        if(isset($this->attributes['created_at']))
            return  Carbon::parse($this->attributes['created_at'])
                ->format('Y/m/d , H:i');
        return  Carbon::now()
            ->format('Y/m/d , H:i');
    }

    function getUserCommentAttribute($val){
        if($val == null){
            return  '';
        }else{
            return $val;
        }
    }

    function getDriverCommentAttribute($val){
        if($val == null){
            return  '';
        }else{
            return $val;
        }
    }

    function getCanceledByAttribute($val){
        if($val == null){
            return  "0";
        }else{
            return $val;
        }
    }

    function getCancelIdAttribute($val){
        if($val == null){
            return  0;
        }else{
            return $val;
        }
    }

    function getCancelReasonAttribute($val){
        if($val == null){
            return  '';
        }else{
            return $val;
        }
    }

    function getPromoIdAttribute($val){
        if($val == null){
            return  0;
        }else{
            return $val;
        }
    }

    function getDescriptionAttribute($val){
        if($val == null){
            return  '';
        }else{
            return $val;
        }
    }

    function getDriverIdAttribute($val){
        if($val == null){
            return  0;
        }else{
            return $val;
        }
    }

    function getStatusAttribute($val){
        //
        if($val == null){
            return  0;
        }else{
            return $val;
        }
    }

    function getDateAttribute($val){
        //
        if($val == null){
            return  '';
        }else{
            return $val;
        }
    }

    function getTimeAttribute($val){
        //
        if($val == null){
            return  '';
        }else{
            return $val;
        }
    }

    function getPromoCodeAttribute($val){
        //
        if($val == null){
            return  '';
        }else{
            return $val;
        }
    }

    function getEndAddressAttribute($val){
        //
        if($val == null){
            return  '';
        }else{
            return $val;
        }
    }

    function getEndLatAttribute($val){
        //
        if($val == null){
            return  '';
        }else{
            return $val;
        }
    }

    function getEndLngAttribute($val){
        //
        if($val == null){
            return  '';
        }else{
            return $val;
        }
    }



}
