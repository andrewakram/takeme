<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class Driver extends Authenticatable
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
        'lat','lng','image','suspend','verified','online','wallet_flag','points','promo_code',
        'city_id','bank_name','bank_account_name','bank_account_num','accept','no_of_trips',
        'car_num','car_text','car_level','car_color','color_name','national_id','national_id_type',
        'image','front_car_image','back_car_image','insurance_image','license_image','civil_image',
        'files_completed','country_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','email_verified_at','created_at','updated_at','deleted_at',
    ];

    protected $appends = ['currency'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function driver_documents(){
        return $this->hasOne(DriverDocument::class,"user_id");
    }

    public function orders(){
        return $this->hasMany(Order::class,"delegate_id");
    }

    public function country(){
        return $this->belongsTo(Country::class,"country_id");
    }

    public function car_levell(){
        return $this->belongsTo(CarLevel::class,"car_level");
    }

    public function driver_car_levels(){
        return $this->hasMany(DriverCarLevel::class,"driver_id");
    }

    public function driver_trips_count($driver_id){
        return Trip::where("driver_id",$driver_id)->count();
    }

    public function setImageAttribute($value)
    {
        if(is_file($value) && $value != null){
            $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
            $value->move(public_path('uploads/drivers/images'),$img_name);
            $this->attributes['image'] = $img_name ;
        }

    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/drivers/images/'.$value);
        }else{
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

    public function getCurrencyAttribute()
    {
        if(isset($this->attributes['country_id'])){
            return (string)Country::whereId($this->attributes['country_id'])->first()->currency;
        }
        //$country_id = $this->attributes['country_id'];
        if(!isset($this->attributes['country_id'])){
            return "درهم اماراتي";
        }
//            $country_id=2;
//
    }




}
