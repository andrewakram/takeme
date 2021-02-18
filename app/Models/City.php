<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class City extends Model
{
    use Notifiable;


    protected $table = 'cities';

    protected $fillable = [
        'name', 'image','country_id','code'
    ];

    protected $hidden = [
        'active', 'deleted_at','created_at', 'updated_at','country_id','image'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class,"country_id");
    }

    public function setImageAttribute($value)
    {
        if($value) {
            $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
            $value->move(public_path('/uploads/cities/'),$img_name);
            $this->attributes['image'] = $img_name ;
        }

    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/cities/'.$value);
        }else{
            return asset('/default.png');
        }
    }



}
