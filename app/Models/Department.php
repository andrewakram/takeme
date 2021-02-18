<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Department extends Model
{
    use Notifiable;


    protected $table = 'departments';

    protected $fillable = [
        'name', 'image'
    ];
    protected $hidden = [
        'updated_at','deleted_at',
    ];

    public function setImageAttribute($value)
    {
        if($value) {
            $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
            $value->move(public_path('/uploads/departments/'),$img_name);
            $this->attributes['image'] = $img_name ;
        }

    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/departments/'.$value);
        }else{
            return asset('/default.png');
        }
    }



}
