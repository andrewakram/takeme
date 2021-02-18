<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaptinInfo extends Model
{
    //
    protected $fillable = [
        'driving_license','working_hours',
        'id_image_1','id_image_2','car_license_1','car_license_2',
        'feesh','car_color','user_id','car_image','color_name',
        'car_num','car_model','car_level','accept','online'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at',
    ];

    public function setDrivingLicenseAttribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('captins/licenses'),$img_name);
        $this->attributes['driving_license'] = $img_name ;
    }

    public function getDrivingLicenseAttribute($value)
    {
        if($value)
        {
            return asset('/captins/licenses/'.$value);
        }else{
            return asset('/default.png');
        }
    }
    /***************/
    public function setIdImage1Attribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('captins/id_images_1/'),$img_name);
        $this->attributes['id_image_1'] = $img_name ;
    }

    public function getIdFrontAttribute($value)
    {
        if($value)
        {
            return asset('/captins/id_images_1/'.$value);
        }else{
            return asset('/default.png');
        }
    }
    /***************/
    public function setIdImage2Attribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('captins/id_images_2/'),$img_name);
        $this->attributes['id_image_2'] = $img_name ;
    }

    public function getIdBackAttribute($value)
    {
        if($value)
        {
            return asset('/captins/id_images_2/'.$value);
        }else{
            return asset('/default.png');
        }
    }
    /***************/
    public function setCarLicense1Attribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('captins/car_licenses_1/'),$img_name);
        $this->attributes['car_license_1'] = $img_name ;
    }

    public function getCarLicenseFrontAttribute($value)
    {
        if($value)
        {
            return asset('/captins/car_licenses_1/'.$value);
        }else{
            return asset('/default.png');
        }
    }
    /***************/
    public function setCarLicense2Attribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('captins/car_licenses_2/'),$img_name);
        $this->attributes['car_license_2'] = $img_name ;
    }

    public function getCarLicenseBackAttribute($value)
    {
        if($value)
        {
            return asset('/captins/car_licenses_2/'.$value);
        }else{
            return asset('/default.png');
        }
    }
    /***************/
    public function setFeeshAttribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('captins/feeshs/'),$img_name);
        $this->attributes['feesh'] = $img_name ;
    }

    public function getFeeshAttribute($value)
    {
        if($value)
        {
            return asset('/captins/feeshs/'.$value);
        }else{
            return asset('/default.png');
        }
    }
    /***************/
    /***************/
    public function setCarImageAttribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('captins/car_images/'),$img_name);
        $this->attributes['car_image'] = $img_name ;
    }

    public function getCarImageAttribute($value)
    {
        if($value)
        {
            return asset('/captins/car_images/'.$value);
        }else{
            return asset('/default.png');
        }
    }
    /***************/

}
