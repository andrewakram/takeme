<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Category extends Model
{
    use Notifiable;


    protected $table = 'categories';

    protected $fillable = [
        'name', 'image'
    ];

    protected $hidden = [
        'active', 'deleted_at','created_at', 'updated_at'
    ];

    public function setImageAttribute($value)
    {
        if($value) {
            $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
            $value->move(public_path('/uploads/categories/'),$img_name);
            $this->attributes['image'] = $img_name ;
        }

    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/categories/'.$value);
        }else{
            return asset('/default.png');
        }
    }



}
