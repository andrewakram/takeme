<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Country extends Model
{
    use Notifiable;


    protected $table = 'countries';

    protected $fillable = [
        'name','name_en', 'image','code','currency','code_name','active','price_per_kilo'
    ];

    protected $hidden = [
        'active', 'deleted_at','created_at', 'updated_at'
    ];

    public function setImageAttribute($value)
    {
        if($value) {
            $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
            $value->move(public_path('/uploads/countries/'),$img_name);
            $this->attributes['image'] = $img_name ;
        }

    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/countries/'.$value);
        }else{
            return asset('/default.png');
        }
    }



}
