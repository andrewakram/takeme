<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class DelegateDocument extends Authenticatable
{
    use Notifiable;
    //use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'front_car_image','front_car_flag','front_car_accept',
        'back_car_image','back_car_flag','back_car_accept',
        'insurance_image','insurance_flag','insurance_accept',
        'license_image','license_flag','license_accept',
        'civil_image','civil_flag','civil_accept',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','email_verified_at','created_at','updated_at','deleted_at',
        'front_car_accept',
        'back_car_accept',
        'insurance_accept',
        'license_accept',
        'civil_accept',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

//    public function setPasswordAttribute()
//    {
//        $this->attributes['password'] = Hash::make(request()->password) ;
//    }

//    public function setImageAttribute($value)
//    {
//        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
//        $value->move(public_path('/uploads/delegates/images/'),$img_name);
//        $this->attributes['image'] = $img_name ;
//    }
//
//    public function getImageAttribute($value)
//    {
//        if($value)
//        {
//            return asset('/uploads/delegates/images/'.$value);
//        }else{
//            return asset('/default.png');
//        }
//    }

    public function setFrontCarImageAttribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('/uploads/delegates/front_car_images/'),$img_name);
        $this->attributes['front_car_image'] = $img_name ;
    }

    public function getFrontCarImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/delegates/front_car_images/'.$value);
        }else{
            return "";
            return asset('/default.png');
        }
    }

    public function setBackCarImageAttribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('/uploads/delegates/back_car_images/'),$img_name);
        $this->attributes['back_car_image'] = $img_name ;
    }

    public function getBackCarImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/delegates/back_car_images/'.$value);
        }else{
            return "";
            return asset('/default.png');
        }
    }

    public function setInsuranceImageAttribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('/uploads/delegates/insurance_images/'),$img_name);
        $this->attributes['insurance_image'] = $img_name ;
    }

    public function getInsuranceImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/delegates/insurance_images/'.$value);
        }else{
            return "";
            return asset('/default.png');
        }
    }

    public function setLicenseImageAttribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('/uploads/delegates/license_images/'),$img_name);
        $this->attributes['license_image'] = $img_name ;
    }

    public function getLicenseImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/delegates/license_images/'.$value);
        }else{
            return "";
            return asset('/default.png');
        }
    }

    public function setCivilImageAttribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('/uploads/delegates/civil_images/'),$img_name);
        $this->attributes['civil_image'] = $img_name ;
    }

    public function getCivilImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/delegates/civil_images/'.$value);
        }else{
            return "";
            return asset('/default.png');
        }
    }

    public function setUserCodeAttribute()
    {
        $this->attributes['user_code'] = rand(100,999) .' - '. Str::random(3) ;
    }

    public function getIsCaptinAttribute($value)
    {
        return (int)$value;
    }

    public function getRateAttribute($value)
    {
        if($value == null)
            return 0;
        return (int)$value;
    }





}
