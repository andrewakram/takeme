<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class UserReplacedPoint extends Model
{
    use Notifiable;
    use SoftDeletes;
//type =>>>> 0=>user, 1=>delegate, 2=>driver

    protected $table = 'user_replaced_points';

    protected $fillable = [
        'user_id','offer_point_id','status','type'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at','status'
    ];

    protected $appends = ['image','description','code','points'];


    public function offer_point(){
        return $this->belongsTo(OfferPoint::class,"offer_point_id");
    }

    public function user(){
        return $this->belongsTo(User::class,"user_id");
    }

    public function getImageAttribute()
    {
        return $this->offer_point()->first()->image;
    }

    public function getDescriptionAttribute()
    {
        return $this->offer_point()->first()->description;
    }

    public function getCodeAttribute()
    {
        return $this->offer_point()->first()->code;
    }

    public function getPointsAttribute()
    {
        return $this->offer_point()->first()->pointrs;
    }

}
