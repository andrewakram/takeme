<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PointCountry extends Model
{
    use Notifiable;


    protected $table = 'points_countries';

    /*protected $fillable = [
        'name_ar', 'name_en', 'image'
    ];*/

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
