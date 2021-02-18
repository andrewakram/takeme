<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class OfferPoint extends Model
{
    use Notifiable;
    use SoftDeletes;


    protected $table = 'offer_points';

    protected $fillable = [
        'image','description','code','points','used'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at',
    ];

    public function setImageAttribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('/uploads/offer_points/'),$img_name);
        $this->attributes['image'] = $img_name ;
    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/offer_points/'.$value);
        }else{
            return "";
        }
    }


}
