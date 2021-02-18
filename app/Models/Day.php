<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Day extends Model
{
    use Notifiable;


    protected $table = 'days';

//    protected $fillable = [
//        'parent_id','department_id','name','email','phone','cover_image',
//        'jwt','image','rate','lat','lng','address','password', 'image','description'
//    ];

    protected $hidden = [
        'active', 'deleted_at','created_at', 'updated_at'
    ];


}
