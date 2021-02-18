<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
//use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use  Notifiable;
    //use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_code','name', 'email', 'password','phone','jwt','token','active',
        'lat','lng','image','suspend','verified','wallet_flag','points','promo_code',
        'country_id','gender','user_country_id','rate','no_of_trips'
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

    public function setImageAttribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('/uploads/users/'),$img_name);
        $this->attributes['image'] = $img_name ;
    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/users/'.$value);
        }else{
            return asset('/default.png');
        }
    }

    public function setUserCodeAttribute()
    {
        $this->attributes['user_code'] = rand(100,999) .' - '. Str::random(3) ;
    }

    public function setPasswordAttribute()
    {
        $this->attributes['password'] = Hash::make(request()->password) ;
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

    public function getUserCountryIdAttribute($value)
    {
        if($value == null)
            return 0;
        return (int)$value;
    }

    public function user_replaced_points(){
        return $this->hasMany(UserReplacedPoint::class,"user_id");
    }

    public function user_wallet_recharges(){
        return $this->hasMany(WalletRecharge::class,"user_id",'id');
    }

    public function country(){
        return $this->belongsTo(Country::class,"country_id");
    }





}
