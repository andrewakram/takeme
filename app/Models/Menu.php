<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Menu extends Model
{
    use Notifiable;
    use SoftDeletes;


    protected $table = 'menus';

//    protected $fillable = [
//        'parent_id','department_id','name','email','phone',
//        'jwt','image','rate','lat','lng','address','password', 'image'
//    ];

    protected $hidden = [
        'active', 'deleted_at','created_at', 'updated_at','shop_id'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'menu_id');
    }





}
