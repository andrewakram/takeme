<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\Authenticatable;
//use Illuminate\Auth\Authenticatable as AuthenticableTrait;
//use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
//use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    //use HasRoles;

    public $guard_name = 'admin';

    protected $fillable = [
        'active','name','email','phone','password','image','app_percent','fee_percent'
    ];

    protected $hidden = [
        'password'
    ];

    public function setImageAttribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('/public/admin/images/'),$img_name);
        $this->attributes['image'] = $img_name ;
    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('/admin/images/'.$value);
        }else{
            return asset('/admin/default.png');
        }
    }
}
