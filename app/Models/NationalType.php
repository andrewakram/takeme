<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class NationalType extends Model
{
    use Notifiable;


    protected $table = 'national_types';

    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at','image','description'
    ];


    public function setImageAttribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('car_types/'),$img_name);
        $this->attributes['image'] = $img_name ;
    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('/car_types/'.$value);
        }else{
            return asset('/default.png');
        }
    }



}
