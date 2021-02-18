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

class Delegate extends Authenticatable
{
    use Notifiable;
    //use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_code','f_name','l_name','email', 'password','phone','jwt','token','active',
        'lat','lng','suspend','verified','online','wallet_flag','points','promo_code',
        'country_id','city_id','bank_name','bank_account_name','bank_account_num','rate','near_orders',
        'car_num','car_text','car_level','national_id','national_id_type','accept','files_completed',
        'image','gender'
//        'front_car_image','back_car_image','insurance_image','license_image','civil_image',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','email_verified_at','created_at','updated_at','deleted_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function delegate_orders_count($delegate_id){
        return Order::where("delegate_id",$delegate_id)->count();
    }

    public function country(){
        return $this->belongsTo(Country::class,"country_id");
    }

    public function delegate_documents(){
        return $this->hasOne(DelegateDocument::class,"user_id");
    }

    public function orders(){
        return $this->hasMany(Order::class,"delegate_id");
    }

    public function car_type(){
        return $this->belongsTo(CarType::class,"car_level");
    }

    public function setPasswordAttribute()
    {
        $this->attributes['password'] = Hash::make(request()->password) ;
    }

    public function setImageAttribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('/uploads/delegates/images/'),$img_name);
        $this->attributes['image'] = $img_name ;
    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/delegates/images/'.$value);
        }else{
            return asset('/default.png');
        }
    }

//    public function setFrontCarImageAttribute($value)
//    {
//        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
//        $value->move(public_path('/uploads/delegates/front_car_images/'),$img_name);
//        $this->attributes['front_car_image'] = $img_name ;
//    }
//
//    public function getFrontCarImageAttribute($value)
//    {
//        if($value)
//        {
//            return asset('/uploads/delegates/front_car_images/'.$value);
//        }else{
//            return asset('/default.png');
//        }
//    }
//
//    public function setBackCarImageAttribute($value)
//    {
//        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
//        $value->move(public_path('/uploads/delegates/back_car_images/'),$img_name);
//        $this->attributes['back_car_image'] = $img_name ;
//    }
//
//    public function getBackCarImageAttribute($value)
//    {
//        if($value)
//        {
//            return asset('/uploads/delegates/back_car_images/'.$value);
//        }else{
//            return asset('/default.png');
//        }
//    }
//
//    public function setInsuranceImageAttribute($value)
//    {
//        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
//        $value->move(public_path('/uploads/delegates/insurance_images/'),$img_name);
//        $this->attributes['insurance_image'] = $img_name ;
//    }
//
//    public function getInsuranceImageAttribute($value)
//    {
//        if($value)
//        {
//            return asset('/uploads/delegates/insurance_images/'.$value);
//        }else{
//            return asset('/default.png');
//        }
//    }
//
//    public function setLicenseImageAttribute($value)
//    {
//        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
//        $value->move(public_path('/uploads/delegates/license_images/'),$img_name);
//        $this->attributes['license_image'] = $img_name ;
//    }
//
//    public function getLicenseImageAttribute($value)
//    {
//        if($value)
//        {
//            return asset('/uploads/delegates/license_images/'.$value);
//        }else{
//            return asset('/default.png');
//        }
//    }
//
//    public function setCivilImageAttribute($value)
//    {
//        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
//        $value->move(public_path('/uploads/delegates/civil_images/'),$img_name);
//        $this->attributes['civil_image'] = $img_name ;
//    }
//
//    public function getCivilImageAttribute($value)
//    {
//        if($value)
//        {
//            return asset('/uploads/delegates/civil_images/'.$value);
//        }else{
//            return asset('/default.png');
//        }
//    }

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

    public function getNearOrdersAttribute($value)
    {
        if($value == null || $value ==0)
            return false;
        return true;
    }

    public function getVerifiedAttribute($value)
    {
        if($value)
            return $value;
        return 0;
    }

    public function getUserCodeAttribute($value)
    {
        if($value)
            return (string)$value;
        return "";
    }

    public function getCityIdAttribute($value)
    {
        if($value)
            return (int)$value;
        return (int)"";
    }

    public function getLatAttribute($value)
    {
        if($value)
            return (string)$value;
        return "";
    }

    public function getLngAttribute($value)
    {
        if($value)
            return (string)$value;
        return "";
    }

    public function getPromoCodeAttribute($value)
    {
        if($value)
            return (string)$value;
        return "";
    }

    public function getBankNameAttribute($value)
    {
        if($value)
            return (string)$value;
        return "";
    }

    public function getBankAccountNameAttribute($value)
    {
        if($value)
            return (string)$value;
        return "";
    }

    public function getBankAccountNumAttribute($value)
    {
        if($value)
            return (string)$value;
        return "";
    }

    public function getCarNumAttribute($value)
    {
        if($value)
            return (string)$value;
        return "";
    }

    public function getCarTextAttribute($value)
    {
        if($value)
            return (string)$value;
        return "";
    }

    public function getCarLevelAttribute($value)
    {
        if($value)
            return (int)$value;
        return (int)"";
    }

    public function getNationalIdAttribute($value)
    {
        if($value)
            return (string)$value;
        return "";
    }

    public function getNationalIdTypeAttribute($value)
    {
        if($value)
            return (string)$value;
        return "";
    }





}
